<?php

namespace App\Livewire\Customer;

use App\Models\Building;
use App\Models\FacilityType;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LandingPage extends Component
{
    public $currentYear;
    public $currentMonth;

    public function mount()
    {
        $this->currentYear = (int) date('Y');
        $this->currentMonth = (int) date('n');
    }

    public function prevMonth()
    {
        if ($this->currentMonth == 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
    }

    public function nextMonth()
    {
        if ($this->currentMonth == 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
    }

    public function setToday()
    {
        $this->currentYear = (int) date('Y');
        $this->currentMonth = (int) date('n');
    }

    public function render()
    {
        // Statistics
        $totalBuildings = Building::active()->count();
        $totalCompleted = Reservation::whereIn('status', ['COMPLETED', 'CONFIRMED'])->count();
        
        // Facility Types (Featured)
        $facilityTypes = FacilityType::with(['images', 'activeBuildings'])
            ->has('activeBuildings')
            ->get();

        // Total active buildings for calendar logic
        $totalActiveBuildings = max(1, $totalBuildings);

        // Fetch bookings for the current month
        $start = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth()->subDays(7);
        $end = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth()->addDays(7);

        $bookings = Reservation::whereNotIn('status', ['REJECTED', 'EXPIRED', 'CANCELED'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_date', '<', $start)
                         ->where('end_date', '>', $end);
                  });
            })
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'building_id' => $booking->building_id,
                'start' => $booking->start_date->format('Y-m-d'),
                'end' => $booking->end_date->format('Y-m-d'),
                'is_pending' => in_array($booking->status->value, ['PENDING_BILLING', 'WAITING_PAYMENT', 'VERIFYING']),
                'customer_name' => $booking->customer_data['nama'] ?? $booking->customer_data['name'] ?? 'Pelanggan',
                'building_name' => $booking->building->name ?? 'Gedung',
                'status' => $booking->status->value,
            ];
        });

        return view('livewire.customer.landing-page', compact(
            'totalBuildings',
            'totalCompleted',
            'facilityTypes',
            'totalActiveBuildings',
            'events'
        ))->layout('layouts.guest');
    }
}
