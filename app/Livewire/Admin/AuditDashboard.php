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
    public string  $payment_id            = '';
    public string  $verified_billing_code = '';
    public string  $verified_amount       = '';
    public string  $audit_decision        = '';
    public string  $rejection_reason      = '';
    public string  $ntb                   = '';
    public string  $ntpn                  = '';
    public bool    $submitted             = false;
    public bool    $zoom_open             = false;
    public ?Payment $payment              = null;

    public function mount(Payment $payment): void
    {
        $this->payment_id = $payment->id;
        $this->payment    = $payment->load(['reservation.user', 'reservation.building']);

        // Pre-fill from parsed SIMPONI data
        $this->verified_billing_code = $this->payment->simponi_billing_code ?? '';
        $this->verified_amount       = $this->payment->nominal
            ? number_format((float) $this->payment->nominal, 0, ',', '')
            : '';
        $this->ntpn                  = $this->payment->ntpn ?? '';
    }

    public function submitAudit(
        ReservationService       $reservationService,
        FonnteNotificationService $fonnte
    ): void {
        $this->validate([
            'verified_billing_code' => ['required', 'digits:15'],
            'verified_amount'       => ['required', 'numeric', 'min:1'],
            'audit_decision'        => ['required', 'in:APPROVE,REJECT'],
            'rejection_reason'      => ['required_if:audit_decision,REJECT', 'nullable', 'string', 'max:500'],
            'ntb'                   => ['required_if:audit_decision,APPROVE', 'nullable', 'string', 'max:50'],
            'ntpn'                  => ['required_if:audit_decision,APPROVE', 'nullable', 'string', 'max:50'],
        ], [
            'verified_billing_code.required' => 'Kode billing wajib diisi.',
            'verified_billing_code.digits'   => 'Kode billing harus tepat 15 digit.',
            'verified_amount.required'       => 'Nominal wajib diisi.',
            'verified_amount.min'            => 'Nominal harus > 0.',
            'audit_decision.required'        => 'Keputusan wajib dipilih.',
            'rejection_reason.required_if'   => 'Alasan penolakan wajib diisi.',
            'ntb.required_if'                => 'NTB wajib diisi jika disetujui.',
            'ntpn.required_if'               => 'NTPN wajib diisi jika disetujui.',
        ]);

        $reservation = $this->payment->reservation;

        // Record audit log
        AuditLog::create([
            'admin_id'   => auth()->id(),
            'payment_id' => $this->payment->id,
            'action'     => $this->audit_decision,
            'payload'    => [
                'verified_billing_code' => $this->verified_billing_code,
                'verified_amount'       => (float) str_replace(',', '', $this->verified_amount),
                'rejection_reason'      => $this->rejection_reason ?: null,
                'ntb'                   => $this->audit_decision === 'APPROVE' ? $this->ntb : null,
                'ntpn'                  => $this->audit_decision === 'APPROVE' ? $this->ntpn : null,
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
                'ntb' => $this->ntb,
                'ntpn' => $this->ntpn,
            ]);
            
            $reservationService->confirm($reservation);
            
            // Stamp the SIMPONI billing PDF as PAID with details
            if ($this->payment->simponi_pdf_path) {
                $stamper = new \App\Services\PdfStamperService();
                $stamper->fillSimponiBpn($this->payment->simponi_pdf_path, [
                    'tanggal_bayar' => now()->format('d-m-Y H:i:s'),
                    'ntb' => $this->ntb,
                    'ntpn' => $this->ntpn,
                ]);
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
