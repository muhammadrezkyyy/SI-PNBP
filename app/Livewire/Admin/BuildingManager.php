<?php

namespace App\Livewire\Admin;

use App\Models\Building;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Manajemen Fasilitas — SI-RESERVASI PNBP')]
#[Layout('layouts.admin')]
class BuildingManager extends Component
{
    use WithPagination;

    public $facilityTypes;
    
    // Form state
    public $buildingId = null;
    public $name = '';
    public $facility_type_id = '';
    public $is_active = true;
    
    // UI state
    public $isModalOpen = false;
    public $isEditing = false;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'facility_type_id' => 'required|exists:facility_types,id',
    ];
    
    public function mount()
    {
        $this->loadBuildings();
        $this->loadFacilityTypes();
    }
    
    public function loadFacilityTypes()
    {
        $query = \App\Models\FacilityType::orderBy('name');
        
        if ($this->isEditing && $this->buildingId) {
            $buildingId = $this->buildingId;
            $query->whereDoesntHave('buildings', function ($q) use ($buildingId) {
                $q->where('id', '!=', $buildingId);
            });
        } else {
            $query->whereDoesntHave('buildings');
        }
        
        $this->facilityTypes = $query->get();
        if ($this->facilityTypes->isNotEmpty() && empty($this->facility_type_id)) {
            $this->facility_type_id = $this->facilityTypes->first()->id;
        }
    }

    public function loadBuildings()
    {
        // Moved to render() for pagination
    }
    
    public function create()
    {
        $this->resetValidation();
        $this->reset(['buildingId', 'name']);
        if ($this->facilityTypes->isNotEmpty()) {
            $this->facility_type_id = $this->facilityTypes->first()->id;
        }
        $this->is_active = true;
        $this->isEditing = false;
        $this->loadFacilityTypes();
        $this->isModalOpen = true;
    }
    
    public function edit($id)
    {
        $this->resetValidation();
        $building = Building::findOrFail($id);
        
        $this->buildingId = $building->id;
        $this->name = $building->name;
        $this->facility_type_id = $building->facility_type_id;
        $this->is_active = $building->is_active;
        
        $this->isEditing = true;
        $this->loadFacilityTypes();
        $this->isModalOpen = true;
    }
    
    public function save()
    {
        $this->validate();
        
        Building::updateOrCreate(
            ['id' => $this->buildingId],
            [
                'name' => $this->name,
                'facility_type_id' => $this->facility_type_id,
                'is_active' => $this->is_active,
            ]
        );
        
        $this->isModalOpen = false;
        $this->loadBuildings();
        
        session()->flash('success', $this->isEditing ? 'Data Unit Ruangan berhasil diperbarui!' : 'Unit Ruangan baru berhasil ditambahkan!');
    }
    
    public function toggleStatus($id)
    {
        $building = Building::findOrFail($id);
        $building->is_active = !$building->is_active;
        $building->save();
        
        $this->loadBuildings();
        session()->flash('success', 'Status gedung berhasil diubah.');
    }
    
    public function delete($id)
    {
        $building = Building::findOrFail($id);
        $building->delete();
        $this->loadBuildings();
        session()->flash('success', 'Unit ruangan berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function render()
    {
        $buildings = Building::with('facilityType')->orderBy('name')->paginate(9);
        return view('livewire.admin.building-manager', compact('buildings'));
    }
}
