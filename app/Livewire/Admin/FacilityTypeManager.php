<?php

namespace App\Livewire\Admin;

use App\Models\FacilityType;
use App\Models\FacilityTypeImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class FacilityTypeManager extends Component
{
    use WithFileUploads, WithPagination;

    public $typeId;
    
    // Form fields
    public $name;
    public $description;
    public $daily_rate;
    public $coverImage;       // Single cover image upload
    public $oldCoverImage;    // Existing cover image path
    public $galleryImages = []; // Multiple gallery uploads
    public $existingGallery = []; // Existing gallery images from DB

    public $isEditing = false;
    public $showModal = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:facility_types,name' . ($this->isEditing ? ',' . $this->typeId : ''),
            'description' => 'required|string',
            'daily_rate' => 'required|numeric|min:0',
            'coverImage' => 'nullable|image|max:2048',
            'galleryImages.*' => 'image|max:2048',
        ];
        return $rules;
    }

    public function mount()
    {
        $this->loadTypes();
    }

    public function loadTypes()
    {
        // Moved to render()
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        
        $type = FacilityType::with('images')->findOrFail($id);
        $this->typeId = $type->id;
        $this->name = $type->name;
        $this->description = $type->description;
        $this->daily_rate = (int) $type->daily_rate;
        $this->oldCoverImage = $type->image_path;
        $this->existingGallery = $type->images->map(fn($img) => [
            'id' => $img->id,
            'path' => $img->image_path,
        ])->toArray();

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Handle cover image
        $coverPath = $this->oldCoverImage;
        if ($this->coverImage) {
            if ($this->oldCoverImage) {
                Storage::disk('public')->delete($this->oldCoverImage);
            }
            $coverPath = $this->coverImage->store('facility-types', 'public');
        }

        if ($this->isEditing) {
            $type = FacilityType::findOrFail($this->typeId);
            $type->update([
                'name' => $this->name,
                'description' => $this->description,
                'daily_rate' => $this->daily_rate,
                'image_path' => $coverPath,
            ]);
            session()->flash('success', 'Kategori fasilitas berhasil diperbarui.');
        } else {
            $type = FacilityType::create([
                'name' => $this->name,
                'description' => $this->description,
                'daily_rate' => $this->daily_rate,
                'image_path' => $coverPath,
            ]);
            session()->flash('success', 'Kategori fasilitas baru berhasil ditambahkan.');
        }

        // Handle gallery images
        if (!empty($this->galleryImages)) {
            $maxOrder = $type->images()->max('sort_order') ?? 0;
            foreach ($this->galleryImages as $index => $img) {
                $path = $img->store('facility-types/gallery', 'public');
                $type->images()->create([
                    'image_path' => $path,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }

        $this->showModal = false;
        $this->loadTypes();
    }

    public function removeGalleryImage($imageId)
    {
        $image = FacilityTypeImage::find($imageId);
        if ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            
            // Update existingGallery state
            $this->existingGallery = collect($this->existingGallery)
                ->reject(fn($img) => $img['id'] == $imageId)
                ->values()
                ->toArray();
        }
    }

    public function delete($id)
    {
        $type = FacilityType::with('images')->findOrFail($id);
        
        // Delete cover image
        if ($type->image_path) {
            Storage::disk('public')->delete($type->image_path);
        }
        // Delete all gallery images
        foreach ($type->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        
        $type->delete();
        $this->loadTypes();
        session()->flash('success', 'Kategori fasilitas berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->typeId = null;
        $this->name = '';
        $this->description = '';
        $this->daily_rate = '';
        $this->coverImage = null;
        $this->oldCoverImage = null;
        $this->galleryImages = [];
        $this->existingGallery = [];
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $types = FacilityType::with('images')->withCount('activeBuildings')->orderBy('name')->paginate(6);
        return view('livewire.admin.facility-type-manager', compact('types'))->layout('layouts.admin');
    }
}
