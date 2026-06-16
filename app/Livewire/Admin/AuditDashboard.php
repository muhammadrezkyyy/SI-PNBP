<?php

namespace App\Livewire\Admin;

use App\Http\Requests\SubmitAuditRequest;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Reservation;
use App\Services\FonnteNotificationService;
use App\Services\ReservationService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Audit Pembayaran — Admin')]
class AuditDashboard extends Component
{
    public string  $audit_decision        = '';
    public string  $rejection_reason      = '';
    public array   $simponi_data          = [];
    public bool    $submitted             = false;
    public bool    $zoom_open             = false;
    public ?Payment $payment              = null;

    public function mount(Payment $payment): void
    {
        $this->payment    = $payment->load(['reservation.user', 'reservation.building']);

        // Parse full SIMPONI data from the PDF file if it exists
        if ($this->payment->simponi_pdf_path) {
            $disk = \Illuminate\Support\Facades\Storage::disk('local');
            if ($disk->exists($this->payment->simponi_pdf_path)) {
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile($disk->path($this->payment->simponi_pdf_path));
                    $rawText = $pdf->getText();
                    
                    $simponiParser = app(\App\Services\SimponiParserService::class);
                    $parsed = $simponiParser->parsePdf($rawText);
                    
                    if (isset($parsed['full_data'])) {
                        $this->simponi_data = $parsed['full_data'];
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to parse PDF in AuditDashboard: ' . $e->getMessage());
                }
            }
        }
        
        // Ensure defaults for form binding if parsing failed or fields are missing
        $defaults = [
            'header_1' => 'Kementerian Keuangan RI',
            'header_2' => 'Direktorat Jenderal Anggaran',
            'header_3' => 'SISTEM INFORMASI PNBP ONLINE (SIMPONI)',
            'title_1'  => 'BUKTI PENERIMAAN NEGARA',
            'title_2'  => 'PENERIMAAN NEGARA BUKAN PAJAK (PNBP)',
            'kode_billing' => '', 'tanggal_billing' => '', 'tanggal_kedaluwarsa' => '',
            'tanggal_bayar' => '', 'bank_pos_fintech_bayar' => '', 'channel_bayar' => '',
            'nama_wajib_setor' => '', 'kementerian_lembaga' => '', 'unit_eselon_i' => '',
            'satuan_kerja' => '', 'total_disetor' => '', 'terbilang' => '',
            'status' => 'Sudah Dibayar', 'ntb' => '', 'ntpn' => '',
            'jenis_setoran' => '', 'kode_akun' => '', 'jumlah_setoran' => '', 'keterangan' => ''
        ];
        $this->simponi_data = array_merge($defaults, $this->simponi_data);
    }

    public function submitAudit(
        ReservationService       $reservationService,
        FonnteNotificationService $fonnte
    ): void {
        $this->validate([
            'audit_decision'                        => ['required', 'in:APPROVE,REJECT'],
            'rejection_reason'                      => ['required_if:audit_decision,REJECT', 'nullable', 'string', 'max:500'],
            'simponi_data.kode_billing'             => ['required', 'digits:15'],
            'simponi_data.total_disetor'            => ['required', 'string'],
            'simponi_data.ntb'                      => ['required_if:audit_decision,APPROVE', 'nullable', 'string', 'max:50'],
            'simponi_data.ntpn'                     => [
                'required_if:audit_decision,APPROVE',
                'nullable',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('payments', 'ntpn')->ignore($this->payment->id),
            ],
            'simponi_data.tanggal_bayar'            => ['required_if:audit_decision,APPROVE', 'nullable', 'string'],
        ], [
            'simponi_data.kode_billing.required'    => 'Kode billing wajib diisi.',
            'simponi_data.kode_billing.digits'      => 'Kode billing harus tepat 15 digit.',
            'simponi_data.total_disetor.required'   => 'Nominal wajib diisi.',
            'audit_decision.required'               => 'Keputusan wajib dipilih.',
            'rejection_reason.required_if'          => 'Alasan penolakan wajib diisi.',
            'simponi_data.ntb.required_if'          => 'NTB wajib diisi jika disetujui.',
            'simponi_data.ntpn.required_if'         => 'NTPN wajib diisi jika disetujui.',
            'simponi_data.ntpn.unique'              => 'NTPN ini sudah digunakan pada pembayaran lain.',
            'simponi_data.tanggal_bayar.required_if'=> 'Tanggal bayar wajib diisi jika disetujui.',
        ]);

        $reservation = $this->payment->reservation;

        // Record audit log
        AuditLog::create([
            'admin_id'   => auth()->id(),
            'payment_id' => $this->payment->id,
            'action'     => $this->audit_decision,
            'payload'    => [
                'kode_billing'          => $this->simponi_data['kode_billing'],
                'rejection_reason'      => $this->rejection_reason ?: null,
                'ntb'                   => $this->audit_decision === 'APPROVE' ? $this->simponi_data['ntb'] : null,
                'ntpn'                  => $this->audit_decision === 'APPROVE' ? $this->simponi_data['ntpn'] : null,
                'tanggal_bayar'         => $this->audit_decision === 'APPROVE' ? $this->simponi_data['tanggal_bayar'] : null,
                'simponi_data_full'     => $this->simponi_data,
                'audited_at'            => now()->toIso8601String(),
                'auditor_ip'            => request()->ip(),
            ],
        ]);

        // Transition FSM
        $phoneNumber = $reservation->user->phone_number
            ?? ($reservation->customer_data['whatsapp'] ?? null)
            ?? ($reservation->customer_data['phone'] ?? null)
            ?? ($reservation->customer_data['no_telp'] ?? null);

        if ($this->audit_decision === 'APPROVE') {
            // Update payment with NTB and verified NTPN
            $this->payment->update([
                'ntb' => $this->simponi_data['ntb'],
                'ntpn' => $this->simponi_data['ntpn'],
            ]);
            
            $reservationService->confirm($reservation);
            
            // Generate New PDF
            $pdfGenerator = app(\App\Services\PdfGeneratorService::class);
            $newPdfPath = $pdfGenerator->generateSimponiPdf($this->simponi_data);
            
            if ($newPdfPath) {
                $this->payment->update(['simponi_pdf_path' => $newPdfPath]);
            }

            if ($phoneNumber) {
                $fonnte->sendConfirmation($phoneNumber, $reservation->id);
            }
        } else {
            $reservationService->reject($reservation);
            if ($phoneNumber) {
                $fonnte->sendRejection($phoneNumber, $this->rejection_reason);
            }
        }

        $this->submitted = true;
        $this->dispatch('audit-submitted', decision: $this->audit_decision);
    }

    public function reprintPdf(\App\Services\PdfGeneratorService $pdfGenerator)
    {
        if ($this->payment && $this->payment->audit_status === 'APPROVED') {
            $newPdfPath = $pdfGenerator->generateSimponiPdf($this->simponi_data);
            if ($newPdfPath) {
                $this->payment->update(['simponi_pdf_path' => $newPdfPath]);
                $this->dispatch('simponi-pdf-updated');
                session()->flash('message', 'PDF berhasil diperbarui dan dicetak ulang.');
            }
        }
    }

    public function toggleZoom(): void
    {
        $this->zoom_open = !$this->zoom_open;
    }

    public function render()
    {
        return view('livewire.admin.audit-dashboard')
            ->layout('layouts.admin');
    }
}
