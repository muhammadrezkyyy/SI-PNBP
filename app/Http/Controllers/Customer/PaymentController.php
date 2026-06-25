<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportPaymentRequest;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct(
        private readonly ReservationService $reservationService,
    ) {}

    /**
     * Show the payment submission form.
     * Accessible via public link (UUID acts as secret token, sent via WhatsApp).
     */
    public function show(Reservation $reservation, Request $request)
    {
        abort_unless($reservation->payment, 404, 'Tagihan belum dibuat oleh admin.');

        return view('customer.payment.show', [
            'reservation' => $reservation->load(['building', 'payment']),
        ]);
    }

    /**
     * Store the NTPN and receipt image submitted by the customer.
     */
    public function store(ReportPaymentRequest $request, Reservation $reservation, \App\Services\FonnteNotificationService $fonnte): RedirectResponse
    {
        // Store receipt image to default disk: 's3' di Railway, 'local' di lokal
        $disk        = config('filesystems.default', 'local');
        $receiptPath = $request->file('receipt_image')->store('receipts', $disk);

        // Update payment record with receipt
        $reservation->payment->update([
            'receipt_path' => $receiptPath,
        ]);

        // Transition reservation status to VERIFYING
        $this->reservationService->transitionToVerifying($reservation);

        // Notify Admin via WhatsApp
        $customerName = $reservation->customer_data['nama']
            ?? $reservation->customer_data['name']
            ?? ($request->user()->name ?? 'Pelanggan');
        $fonnte->sendPaymentProofNotificationToAdmin($reservation->id, $customerName);

        return redirect()
            ->route('customer.payment.show', $reservation)
            ->with('success', 'Bukti pembayaran berhasil diupload. Admin akan memverifikasi dalam 1x24 jam.');
    }

    /**
     * View the SIMPONI tagihan PDF.
     */
    public function viewSimponi(\App\Models\Payment $payment)
    {
        if (! $payment->simponi_pdf_path) {
            abort(404, 'File tagihan SIMPONI tidak ditemukan.');
        }

        $disk    = config('filesystems.default', 'local');
        $storage = \Illuminate\Support\Facades\Storage::disk($disk);

        if (! $storage->exists($payment->simponi_pdf_path)) {
            abort(404, 'File tagihan SIMPONI tidak ditemukan.');
        }

        if ($disk === 'local') {
            return response()->file($storage->path($payment->simponi_pdf_path));
        }

        // S3/R2: stream file langsung
        return response($storage->get($payment->simponi_pdf_path), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]);
    }
}
