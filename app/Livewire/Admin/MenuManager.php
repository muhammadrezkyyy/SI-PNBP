<?php

namespace App\Livewire\Admin;

use App\Models\AdminMenu;
use Livewire\Component;

class MenuManager extends Component
{
    public $menus;

    public $menuId;
    public $group;
    public $label;
    public $route;
    public $icon;
    public $is_active = true;

    public $isEditing = false;
    public $showModal = false;

    protected $rules = [
        'group' => 'nullable|string|max:255',
        'label' => 'required|string|max:255',
        'route' => 'required|string|max:255',
        'icon'  => 'required|string',
    ];

    public function mount()
    {
        $this->loadMenus();
    }

    public function loadMenus()
    {
        $this->menus = AdminMenu::orderBy('order')->get();
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
        
        $menu = AdminMenu::findOrFail($id);
        $this->menuId = $menu->id;
        $this->group = $menu->group;
        $this->label = $menu->label;
        $this->route = $menu->route;
        $this->icon = $menu->icon;
        $this->is_active = $menu->is_active;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $menu = AdminMenu::findOrFail($this->menuId);
            $menu->update([
                'group' => $this->group ?: null,
                'label' => $this->label,
                'route' => $this->route,
                'icon'  => $this->icon,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Menu berhasil diperbarui.');
        } else {
            $maxOrder = AdminMenu::max('order') ?? 0;
            AdminMenu::create([
                'group' => $this->group ?: null,
                'label' => $this->label,
                'route' => $this->route,
                'icon'  => $this->icon,
                'order' => $maxOrder + 1,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Menu baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->loadMenus();
    }

    public function delete($id)
    {
        AdminMenu::findOrFail($id)->delete();
        $this->loadMenus();
        session()->flash('success', 'Menu berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $menu = AdminMenu::findOrFail($id);
        $menu->is_active = !$menu->is_active;
        $menu->save();
        $this->loadMenus();
    }

    public function moveUp($id)
    {
        $menu = AdminMenu::findOrFail($id);
        $prev = AdminMenu::where('order', '<', $menu->order)->orderBy('order', 'desc')->first();
        if ($prev) {
            $tempOrder = $menu->order;
            $menu->order = $prev->order;
            $prev->order = $tempOrder;
            $menu->save();
            $prev->save();
            $this->loadMenus();
        }
    }

    public function moveDown($id)
    {
        $menu = AdminMenu::findOrFail($id);
        $next = AdminMenu::where('order', '>', $menu->order)->orderBy('order', 'asc')->first();
        if ($next) {
            $tempOrder = $menu->order;
            $menu->order = $next->order;
            $next->order = $tempOrder;
            $menu->save();
            $next->save();
            $this->loadMenus();
        }
    }

    public function resetForm()
    {
        $this->menuId = null;
        $this->group = '';
        $this->label = '';
        $this->route = '';
        $this->icon = '';
        $this->is_active = true;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.menu-manager')->layout('layouts.admin');
    }
}
