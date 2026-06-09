<?php

namespace App\Livewire\Admin;

use App\Models\BookingFormField;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Form Builder — Admin')]
class FormBuilder extends Component
{
    public array $fields = [];
    public bool  $saved  = false;

    public function mount(): void
    {
        $this->loadFields();
    }

    private function loadFields(): void
    {
        $this->fields = BookingFormField::ordered()
            ->get()
            ->map(fn($f) => [
                'id'          => $f->id,
                'field_name'  => $f->field_name,
                'field_label' => $f->field_label,
                'field_type'  => $f->field_type,
                'is_required' => $f->is_required,
                'placeholder' => $f->placeholder ?? '',
                'sort_order'  => $f->sort_order,
            ])
            ->toArray();
    }

    public function addField(): void
    {
        $this->fields[] = [
            'id'          => null,
            'field_name'  => '',
            'field_label' => '',
            'field_type'  => 'text',
            'is_required' => true,
            'placeholder' => '',
            'sort_order'  => count($this->fields),
        ];
    }

    public function removeField(int $index): void
    {
        $field = $this->fields[$index] ?? null;

        if ($field && $field['id']) {
            BookingFormField::destroy($field['id']);
        }

        array_splice($this->fields, $index, 1);
        $this->reorderSortOrder();
        $this->saved = false;
    }

    public function moveUp(int $index): void
    {
        if ($index <= 0) return;
        [$this->fields[$index], $this->fields[$index - 1]] = [$this->fields[$index - 1], $this->fields[$index]];
        $this->reorderSortOrder();
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->fields) - 1) return;
        [$this->fields[$index], $this->fields[$index + 1]] = [$this->fields[$index + 1], $this->fields[$index]];
        $this->reorderSortOrder();
    }

    private function reorderSortOrder(): void
    {
        foreach ($this->fields as $i => &$field) {
            $field['sort_order'] = $i;
        }
    }

    public function saveForm(): void
    {
        $this->validate([
            'fields'                => 'required|array|min:1',
            'fields.*.field_name'   => 'required|string|max:100|regex:/^[a-z_]+$/',
            'fields.*.field_label'  => 'required|string|max:200',
            'fields.*.field_type'   => 'required|in:text,number,date,email,textarea',
            'fields.*.is_required'  => 'boolean',
            'fields.*.placeholder'  => 'nullable|string|max:200',
        ], [
            'fields.*.field_name.regex'    => 'Nama field hanya boleh huruf kecil dan underscore.',
            'fields.*.field_name.required' => 'Nama field wajib diisi.',
            'fields.*.field_label.required' => 'Label field wajib diisi.',
        ]);

        // Upsert all fields
        foreach ($this->fields as $index => $fieldData) {
            BookingFormField::updateOrCreate(
                ['id' => $fieldData['id'] ?: \Illuminate\Support\Str::uuid()->toString()],
                [
                    'field_name'  => $fieldData['field_name'],
                    'field_label' => $fieldData['field_label'],
                    'field_type'  => $fieldData['field_type'],
                    'is_required' => $fieldData['is_required'],
                    'placeholder' => $fieldData['placeholder'],
                    'sort_order'  => $index,
                ]
            );
        }

        $this->loadFields();
        $this->saved = true;

        $this->dispatch('form-saved');
    }

    public function render()
    {
        return view('livewire.admin.form-builder')
            ->layout('layouts.admin');
    }
}
