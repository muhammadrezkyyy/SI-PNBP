<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Reservation;
use App\Models\Building;

class ReportManager extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $status = '';
    public $buildingId = '';

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function getBuildingsProperty()
    {
        return Building::orderBy('name')->get();
    }

    public function getQueryProperty()
    {
        $query = Reservation::with(['user', 'building', 'payment']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->buildingId) {
            $query->where('building_id', $this->buildingId);
        }

        return $query;
    }

    public function getSummaryProperty()
    {
        $q1 = clone $this->query;
        $q2 = clone $this->query;
        
        $totalReservations = $q1->count();
        
        $revenue = $q2->whereIn('status', ['CONFIRMED', 'COMPLETED'])
            ->get()
            ->sum(function($r) {
                return $r->payment ? $r->payment->nominal : 0;
            });
            
        return [
            'total_reservations' => $totalReservations,
            'total_revenue' => $revenue,
        ];
    }
    
    public function exportPdf()
    {
        return redirect()->route('admin.reports.export', [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'status' => $this->status,
            'building_id' => $this->buildingId,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.report-manager');
    }
}
