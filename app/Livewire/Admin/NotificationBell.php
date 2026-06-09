<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Reservation;

class NotificationBell extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Reservation::with(['user', 'payment'])
            ->whereIn('status', ['PENDING_BILLING', 'VERIFYING'])
            ->latest()
            ->take(10)
            ->get();

        $this->unreadCount = $this->notifications->count();
    }

    public function render()
    {
        return view('livewire.admin.notification-bell');
    }
}
