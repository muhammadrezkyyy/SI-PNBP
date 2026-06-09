<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Show all reservations with filtering by status.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'building', 'payment'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->search, function ($q, $search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('building', fn($b) => $b->where('name', 'like', "%{$search}%"));
            })
            ->latest();

        $perPage = $request->input('per_page', 15);

        if ($perPage === 'all') {
            $reservations = $query->paginate($query->count() ?: 15)->withQueryString();
        } else {
            $reservations = $query->paginate((int) $perPage)->withQueryString();
        }

        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show a single reservation detail.
     */
    public function show(Reservation $reservation)
    {
        return view('admin.reservations.show', [
            'reservation' => $reservation->load(['user', 'building', 'payment.auditLog.admin']),
        ]);
    }
}
