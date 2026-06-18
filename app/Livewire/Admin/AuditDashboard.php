<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Payment;
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

    /** Full edited HTML content from the Word-like editor */
    public string  $bpn_html             = '';

    /** Positions of draggable elements (QR, logo) in px relative to paper */
    public array   $element_positions    = [];

    public function mount(Payment $payment): void
    {
        $this->payment = $payment->load(['reservation.user', 'reservation.building']);

        // Parse full SIMPONI data from the PDF file if it exists
        if ($this->payment->simponi_pdf_path) {
            $disk = \Illuminate\Support\Facades\Storage::disk('local');
            if ($disk->exists($this->payment->simponi_pdf_path)) {
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf    = $parser->parseFile($disk->path($this->payment->simponi_pdf_path));
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
            'jenis_setoran' => '', 'kode_akun' => '', 'jumlah_setoran' => '', 'keterangan' => '',
        ];
        $this->simponi_data = array_merge($defaults, $this->simponi_data);

        // Default draggable element positions (matching original CSS layout)
        $this->element_positions = [
            'bpn-el-logo'         => ['top' => 0,   'left' => 0,    'useRight'  => false, 'useCenter' => false],
            'bpn-el-qr-header'    => ['top' => 0,   'left' => 609,  'useRight'  => true,  'useCenter' => false],
            'bpn-el-qr-watermark' => ['top' => 210, 'left' => 147,  'useRight'  => false, 'useCenter' => false],
        ];
    }

    /**
     * Save the full edited HTML from the Word-like editor.
     * #[Renderless] agar auto-save tidak trigger re-render komponen.
     */
    #[\Livewire\Attributes\Renderless]
    public function saveBpnContent(string $html): void
    {
        $this->bpn_html = $html;
    }

    /**
     * Save the absolute pixel position of a draggable element.
     * #[Renderless] = simpan data tapi TIDAK trigger re-render komponen,
     * sehingga posisi drag yang sudah diset via JS tidak di-reset.
     */
    #[\Livewire\Attributes\Renderless]
    public function saveElementPosition(string $elId, int $top, int $left): void
    {
        $this->element_positions[$elId] = array_merge(
            $this->element_positions[$elId] ?? [],
            ['top' => $top, 'left' => $left, 'useRight' => false, 'useCenter' => false]
        );
    }

    public function submitAudit(
        ReservationService        $reservationService,
        FonnteNotificationService $fonnte
    ): void {
        $this->validate([
            'audit_decision'                         => ['required', 'in:APPROVE,REJECT'],
            'rejection_reason'                       => ['required_if:audit_decision,REJECT', 'nullable', 'string', 'max:500'],
            'simponi_data.kode_billing'              => ['required', 'digits:15'],
            'simponi_data.total_disetor'             => ['required', 'string'],
            'simponi_data.ntb'                       => ['required_if:audit_decision,APPROVE', 'nullable', 'string', 'max:50'],
            'simponi_data.ntpn'                      => [
                'required_if:audit_decision,APPROVE',
                'nullable', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('payments', 'ntpn')->ignore($this->payment->id),
            ],
            'simponi_data.tanggal_bayar'             => ['required_if:audit_decision,APPROVE', 'nullable', 'string'],
        ], [
            'simponi_data.kode_billing.required'     => 'Kode billing wajib diisi.',
            'simponi_data.kode_billing.digits'       => 'Kode billing harus tepat 15 digit.',
            'simponi_data.total_disetor.required'    => 'Nominal wajib diisi.',
            'audit_decision.required'                => 'Keputusan wajib dipilih.',
            'rejection_reason.required_if'           => 'Alasan penolakan wajib diisi.',
            'simponi_data.ntb.required_if'           => 'NTB wajib diisi jika disetujui.',
            'simponi_data.ntpn.required_if'          => 'NTPN wajib diisi jika disetujui.',
            'simponi_data.ntpn.unique'               => 'NTPN ini sudah digunakan pada pembayaran lain.',
            'simponi_data.tanggal_bayar.required_if' => 'Tanggal bayar wajib diisi jika disetujui.',
        ]);

        $reservation = $this->payment->reservation;

        AuditLog::create([
            'admin_id'   => auth()->id(),
            'payment_id' => $this->payment->id,
            'action'     => $this->audit_decision,
            'payload'    => [
                'kode_billing'      => $this->simponi_data['kode_billing'],
                'rejection_reason'  => $this->rejection_reason ?: null,
                'ntb'               => $this->audit_decision === 'APPROVE' ? $this->simponi_data['ntb'] : null,
                'ntpn'              => $this->audit_decision === 'APPROVE' ? $this->simponi_data['ntpn'] : null,
                'tanggal_bayar'     => $this->audit_decision === 'APPROVE' ? $this->simponi_data['tanggal_bayar'] : null,
                'simponi_data_full' => $this->simponi_data,
                'audited_at'        => now()->toIso8601String(),
                'auditor_ip'        => request()->ip(),
            ],
        ]);

        $phoneNumber = $reservation->user->phone_number
            ?? ($reservation->customer_data['whatsapp'] ?? null)
            ?? ($reservation->customer_data['phone'] ?? null)
            ?? ($reservation->customer_data['no_telp'] ?? null);

        if ($this->audit_decision === 'APPROVE') {
            $this->payment->update([
                'ntb'  => $this->simponi_data['ntb'],
                'ntpn' => $this->simponi_data['ntpn'],
            ]);

            $reservationService->confirm($reservation);

            $pdfGenerator = app(\App\Services\PdfGeneratorService::class);
            $pdfData = array_merge($this->simponi_data, [
                'qr_content' => 'SIMPONI-BILLING-' . ($this->simponi_data['kode_billing'] ?? time()),
            ]);
            $newPdfPath = $pdfGenerator->generateSimponiPdf($pdfData);

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

    public function reprintPdf(\App\Services\PdfGeneratorService $pdfGenerator): void
    {
        if (!$this->payment) return;

        // Jika admin sudah klik "Simpan Editan", $bpn_html berisi full #bpn-paper HTML snapshot
        // → generate PDF 1:1 persis seperti tampilan editor
        // Jika belum pernah klik "Simpan", fallback ke Blade template
        if (!empty($this->bpn_html)) {
            $newPdfPath = $pdfGenerator->generateFromEditedHtml(
                $this->bpn_html,
                $this->simponi_data,
                $this->element_positions
            );
        } else {
            $data = array_merge($this->simponi_data, [
                'qr_content' => 'SIMPONI-BILLING-' . ($this->simponi_data['kode_billing'] ?? time()),
                'ntb'        => $this->simponi_data['ntb']  ?? $this->payment->ntb  ?? '',
                'ntpn'       => $this->simponi_data['ntpn'] ?? $this->payment->ntpn ?? '',
            ]);
            $newPdfPath = $pdfGenerator->generateSimponiPdf($data);
        }

        if ($newPdfPath) {
            // Hapus file lama agar tidak menumpuk
            if ($this->payment->simponi_pdf_path) {
                \Illuminate\Support\Facades\Storage::disk('local')
                    ->delete($this->payment->simponi_pdf_path);
            }
            $this->payment->update(['simponi_pdf_path' => $newPdfPath]);
            $this->dispatch('simponi-pdf-updated');
            $this->dispatch('notify', message: 'PDF berhasil diperbarui. Klik "Buka PDF" untuk mencetak.');
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
