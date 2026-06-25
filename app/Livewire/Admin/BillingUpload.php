<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Reservation;
use App\Services\FonnteNotificationService;
use App\Services\ReservationService;
use App\Services\SimponiParserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Smalot\PdfParser\Parser as PdfParser;

#[Title('Upload Tagihan SIMPONI ?" Admin')]
#[Layout('layouts.admin')]
class BillingUpload extends Component
{
    use WithFileUploads;

    public Reservation $reservation;
    public $simponi_pdf;
    public bool $is_manual = false;
    public string $manual_billing_code = '';
    public string $manual_nominal = '';
    
    // Extracted Data
    public ?string $extracted_billing_code = null;
    public ?float $extracted_nominal = null;
    public ?string $extracted_error = null;
    public ?string $pdf_preview_url = null;
    public ?string $raw_text = '';

    public function mount(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function updatedSimponiPdf()
    {
        $this->resetExtractedData();
        $this->validateOnly('simponi_pdf', [
            'simponi_pdf' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($this->simponi_pdf) {
            // temporaryUrl() hanya support S3/cloud disk.
            // Di local disk akan throw exception, jadi kita fallback ke null.
            try {
                $this->pdf_preview_url = $this->simponi_pdf->temporaryUrl();
            } catch (\RuntimeException $e) {
                // Fallback: preview tidak tersedia di disk local
                $this->pdf_preview_url = null;
            }
            $this->parsePdf();
        }
    }

    private function resetExtractedData()
    {
        $this->extracted_billing_code = null;
        $this->extracted_nominal = null;
        $this->extracted_error = null;
        $this->raw_text = '';
    }

    private function parsePdf()
    {
        $tmpPath = null;
        try {
            $parser = new PdfParser();

            // getRealPath() hanya bisa digunakan pada disk 'local'.
            // Saat menggunakan S3/R2 sebagai temp disk, getRealPath() = '' (kosong).
            // Solusi: download file dari S3 ke temp lokal dulu, baru parse.
            $realPath = $this->simponi_pdf->getRealPath();

            if ($realPath && file_exists($realPath)) {
                // Disk lokal — langsung gunakan path file
                $pdf = $parser->parseFile($realPath);
            } else {
                // S3 / R2 — download konten file ke temp lokal dulu
                $livewireDisk = config('livewire.temporary_file_upload.disk')
                    ?? config('filesystems.default', 'local');

                $fileContent = Storage::disk($livewireDisk)
                    ->get($this->simponi_pdf->getPathname());

                $tmpPath = tempnam(sys_get_temp_dir(), 'sipnbp_') . '.pdf';
                file_put_contents($tmpPath, $fileContent);
                $pdf = $parser->parseFile($tmpPath);
            }

            $this->raw_text = $pdf->getText();

            $simponiParser = app(SimponiParserService::class);
            $parsed = $simponiParser->parsePdf($this->raw_text);

            if ($parsed['status'] === 'success') {
                $this->extracted_billing_code = $parsed['billing_code'];
                $this->extracted_nominal = $parsed['nominal'];

                // Auto-fill manual inputs and enable edit mode
                $this->manual_billing_code = $parsed['billing_code'];
                $this->manual_nominal = $parsed['nominal'];
                $this->is_manual = true;
            } else {
                $this->extracted_error = implode(' ', $parsed['errors']);
                $this->is_manual = true; // Auto open manual mode on error
            }
        } catch (\Exception $e) {
            Log::error('[BillingUpload] parsePdf failed: ' . $e->getMessage());
            $this->extracted_error = 'Gagal membaca PDF. Pastikan file tidak terenkripsi.';
            $this->is_manual = true;
        } finally {
            // Selalu hapus temp file meskipun ada exception
            if ($tmpPath && file_exists($tmpPath)) {
                @unlink($tmpPath);
            }
        }
    }

    public function submit(
        ReservationService $reservationService,
        FonnteNotificationService $fonnte
    ) {
        $this->validate([
            'simponi_pdf' => [$this->is_manual ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        if ($this->is_manual) {
            $this->validate([
                'manual_billing_code' => ['required', 'string', 'size:15'],
                'manual_nominal'      => ['required', 'numeric', 'min:0'],
            ], [
                'manual_billing_code.required' => 'Kode Billing wajib diisi jika mode manual aktif.',
                'manual_billing_code.size'     => 'Kode Billing harus 15 digit.',
                'manual_nominal.required'      => 'Nominal wajib diisi jika mode manual aktif.',
            ]);

            $billingCode = $this->manual_billing_code;
            $nominal = $this->manual_nominal;
            $rawLength = strlen($this->raw_text);
        } else {
            if ($this->extracted_error) {
                $this->addError('simponi_pdf', $this->extracted_error);
                return;
            }
            $billingCode = $this->extracted_billing_code;
            $nominal = $this->extracted_nominal;
            $rawLength = strlen($this->raw_text);
        }

        $pdfPath = null;
        if ($this->simponi_pdf) {
            // Gunakan disk default: 's3' di Railway (Cloudflare R2), 'local' di lokal
            $disk    = config('filesystems.default', 'local');
            $pdfPath = $this->simponi_pdf->store('simponi-pdfs', $disk);
        }

        // Create Payment record
        $payment = Payment::create([
            'reservation_id'       => $this->reservation->id,
            'simponi_billing_code' => $billingCode,
            'nominal'              => $nominal,
            'simponi_pdf_path'     => $pdfPath,
            'ocr_metadata'         => [
                'raw_length'   => $rawLength,
                'parsed_at'    => now()->toIso8601String(),
                'parser'       => 'smalot/pdfparser',
            ],
        ]);

        // Transition reservation status
        $reservationService->transitionToWaitingPayment($this->reservation);

        // Send WhatsApp billing instruction
        // Prioritaskan nomor dari customer_data (yang diisi pelanggan saat booking),
        // karena user->phone_number bisa berisi nomor lain (misal nomor admin jika booking dibuat oleh admin).
        $phoneNumber = ($this->reservation->customer_data['no_telp'] ?? null)
            ?? ($this->reservation->customer_data['whatsapp'] ?? null)
            ?? ($this->reservation->customer_data['phone'] ?? null)
            ?? ($this->reservation->customer_data['no_hp'] ?? null)
            ?? ($this->reservation->customer_data['telepon'] ?? null)
            ?? ($this->reservation->customer_data['nohp'] ?? null)
            ?? ($this->reservation->user?->phone_number ?? null);

        Log::info('[BillingUpload] Mengirim notifikasi WA ke pelanggan.', [
            'reservation_id' => $this->reservation->id,
            'phone_resolved'  => $phoneNumber,
            'customer_data_keys' => array_keys($this->reservation->customer_data ?? []),
        ]);

        if ($phoneNumber) {
            $fonnte->sendBillingInstruction(
                $phoneNumber,
                $billingCode,
                (float) $nominal,
                $this->reservation->id
            );
        }

        session()->flash('success', 'Tagihan SIMPONI berhasil diupload dan instruksi pembayaran telah dikirim via WhatsApp.');
        return redirect()->route('admin.reservations.show', $this->reservation);
    }

    public function render()
    {
        return view('livewire.admin.billing-upload');
    }
}
