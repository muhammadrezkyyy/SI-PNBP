<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileManager extends Component
{
    public $name;
    public $email;
    public $current_password;
    public $password;
    public $password_confirmation;

    public $isModalOpen = false;

    protected $listeners = ['openProfileModal' => 'openModal'];

    public function openModal()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
        
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function save()
    {
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ];

        // Only validate password if it's being changed
        if (!empty($this->password)) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $this->validate($rules);

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->closeModal();
        
        session()->flash('success', 'Profil berhasil diperbarui!');
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.profile-manager');
    }
}
