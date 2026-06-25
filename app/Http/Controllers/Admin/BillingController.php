<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Services\FonnteNotificationService;
use App\Services\ReservationService;
use App\Services\SimponiParserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class BillingController extends Controller
{
    public function __construct(
        private readonly SimponiParserService    $parser,
        private readonly ReservationService      $reservationService,
        private readonly FonnteNotificationService $fonnte,
    ) {}

    /**
     * Show the SIMPONI PDF upload form for a reservation.
     */
    public function showUploadForm(Reservation $reservation)
    {
        return view('admin.billing.upload', compact('reservation'));
    }

    /**
     * Handle SIMPONI PDF upload, parse billing code, create Payment,
     * send WhatsApp notification, transition status.
     */
    public function uploadSimponi(Request $request, Reservation $reservation): RedirectResponse
    {
        $isManual = $request->boolean('is_manual');

        $request->validate([
            'simponi_pdf' => [$isManual ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $pdfPath = null;
        $rawText = '';

        if ($request->hasFile('simponi_pdf')) {
            // Store PDF ke disk default: 's3' di Railway, 'local' di lokal
            $disk    = config('filesystems.default', 'local');
            $pdfPath = $request->file('simponi_pdf')->store('simponi-pdfs', $disk);

            // Extract text from PDF
            try {
                $parser  = new PdfParser();
                $storage = Storage::disk($disk);

                if ($disk === 'local') {
                    $pdf = $parser->parseFile($storage->path($pdfPath));
                } else {
                    // S3/R2: download ke temp dulu
                    $tmpPath = tempnam(sys_get_temp_dir(), 'simponi_') . '.pdf';
                    file_put_contents($tmpPath, $storage->get($pdfPath));
                    $pdf = $parser->parseFile($tmpPath);
                    @unlink($tmpPath);
                }

                $rawText = $pdf->getText();
            } catch (\Exception $e) {
                return back()->withErrors(['simponi_pdf' => 'Gagal membaca PDF. Pastikan file tidak terenkripsi.']);
            }
        }

        if ($isManual) {
            $request->validate([
                'manual_billing_code' => ['required', 'string', 'size:15'],
                'manual_nominal'      => ['required', 'numeric', 'min:0'],
            ], [
                'manual_billing_code.required' => 'Kode Billing wajib diisi jika mode manual aktif.',
                'manual_billing_code.size'     => 'Kode Billing harus 15 digit.',
                'manual_nominal.required'      => 'Nominal wajib diisi jika mode manual aktif.',
            ]);

            $parsed = [
                'billing_code' => $request->input('manual_billing_code'),
                'nominal'      => $request->input('manual_nominal'),
                'status'       => 'success',
                'raw_length'   => strlen($rawText),
                'errors'       => [],
            ];
        } else {
            // Parse billing code and nominal dari PDF
            $parsed = $this->parser->parsePdf($rawText);

            if ($parsed['status'] === 'error') {
                // Hapus PDF yang sudah diupload jika parsing gagal
                $disk = config('filesystems.default', 'local');
                Storage::disk($disk)->delete($pdfPath);
                return back()->withErrors([
                    'simponi_pdf' => implode(' ', $parsed['errors']),
                ])->withInput();
            }
        }

        // Create Payment record
        $payment = Payment::create([
            'reservation_id'       => $reservation->id,
            'simponi_billing_code' => $parsed['billing_code'],
            'nominal'              => $parsed['nominal'],
            'simponi_pdf_path'     => $pdfPath,
            'ocr_metadata'         => [
                'raw_length'   => $parsed['raw_length'],
                'parsed_at'    => now()->toIso8601String(),
                'parser'       => 'smalot/pdfparser',
            ],
        ]);

        // Transition reservation status
        $this->reservationService->transitionToWaitingPayment($reservation);

        // Send WhatsApp billing instruction
        $phoneNumber = $reservation->user->phone_number
            ?? ($reservation->customer_data['whatsapp'] ?? null)
            ?? ($reservation->customer_data['phone'] ?? null);

        if ($phoneNumber) {
            $this->fonnte->sendBillingInstruction(
                $phoneNumber,
                $parsed['billing_code'],
                (float) $parsed['nominal'],
                $reservation->id
            );
        }

        return redirect()
            ->route('admin.reservations.show', $reservation)
            ->with('success', 'Tagihan SIMPONI berhasil diupload dan instruksi pembayaran telah dikirim via WhatsApp.');
    }
}
