<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Building;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show the reports page.
     */
    public function index()
    {
        return view('admin.reports');
    }

    /**
     * Export filtered reservations to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Reservation::with(['user', 'building', 'payment']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        $reservations = $query->latest()->get();

        // Calculate totals
        $totalRevenue = $reservations
            ->filter(fn($r) => in_array($r->status instanceof \App\Enums\ReservationStatus ? $r->status->value : $r->status, ['CONFIRMED', 'COMPLETED']))
            ->sum(fn($r) => $r->payment ? $r->payment->nominal : 0);

        $statusLabels = [
            'PENDING_BILLING' => 'Menunggu Tagihan',
            'WAITING_PAYMENT' => 'Belum Bayar',
            'VERIFYING'       => 'Menunggu Audit',
            'CONFIRMED'       => 'Lunas',
            'COMPLETED'       => 'Selesai',
            'REJECTED'        => 'Ditolak',
        ];

        // Get building name if filtered
        $buildingName = 'Semua Fasilitas';
        if ($request->filled('building_id')) {
            $building = Building::find($request->building_id);
            $buildingName = $building ? $building->name : 'Semua Fasilitas';
        }

        $data = [
            'reservations' => $reservations,
            'totalRevenue'  => $totalRevenue,
            'statusLabels'  => $statusLabels,
            'startDate'     => $request->start_date,
            'endDate'       => $request->end_date,
            'statusFilter'  => $request->status ? ($statusLabels[$request->status] ?? $request->status) : 'Semua',
            'buildingName'  => $buildingName,
            'printDate'     => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.reports-pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Laporan_Reservasi_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}
