<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteNotificationService
{
    private const API_URL = 'https://api.fonnte.com/send';

    /**
     * Send billing instruction to customer via WhatsApp (Fonnte gateway).
     */
    public function sendBillingInstruction(
        string $phoneNumber,
        string $billingCode,
        float  $amount,
        string $reservationId
    ): bool {
        $formattedAmount = 'Rp ' . number_format($amount, 0, ',', '.');
        $message = $this->buildBillingMessage($billingCode, $formattedAmount, $reservationId);

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send payment confirmation to customer.
     */
    public function sendConfirmation(string $phoneNumber, string $reservationId): bool
    {
        $message = "[KONFIRMASI RESERVASI]\n\n"
            . "Pembayaran Anda telah diverifikasi dan reservasi dikonfirmasi.\n"
            . "ID Reservasi: {$reservationId}\n\n"
            . "Terima kasih telah menggunakan layanan kami.";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send rejection notification to customer.
     */
    public function sendRejection(string $phoneNumber, string $reason): bool
    {
        $message = "[PEMBAYARAN DITOLAK]\n\n"
            . "Mohon maaf, pembayaran Anda tidak dapat diverifikasi.\n"
            . "Alasan: {$reason}\n\n"
            . "Silakan upload ulang bukti pembayaran yang valid atau hubungi admin.";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Send notification to admin that customer uploaded payment proof.
     */
    public function sendPaymentProofNotificationToAdmin(string $reservationId, string $customerName): bool
    {
        $adminWa = config('services.fonnte.admin_wa');
        if (empty($adminWa)) {
            Log::warning('[Fonnte] Admin WA number not configured.');
            return false;
        }

        $auditUrl = route('admin.reservations.show', $reservationId);

        $message = "[PEMBAYARAN BARU]\n\n"
            . "Pelanggan *{$customerName}* telah mengupload bukti pembayaran untuk reservasi {$reservationId}.\n\n"
            . "Silakan periksa dan lakukan audit (Approve/Reject) di Dashboard Admin:\n"
            . "{$auditUrl}";

        return $this->send($adminWa, $message);
    }

    /**
     * Core HTTP POST to Fonnte API.
     */
    private function send(string $phoneNumber, string $message): bool
    {
        $token = config('services.fonnte.token');

        if (empty($token)) {
            Log::warning('[Fonnte] Token not configured. Skipping WA notification.', [
                'target' => $phoneNumber,
            ]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post(self::API_URL, [
                'target'  => $phoneNumber,
                'message' => $message,
            ]);

            $json = $response->json();
            $success = $response->successful() && ($json['status'] ?? false) === true;

            if ($success) {
                Log::info('[Fonnte] WA sent successfully.', [
                    'target'   => $phoneNumber,
                    'response' => $json,
                ]);
                return true;
            }

            Log::error('[Fonnte] API returned error.', [
                'target'   => $phoneNumber,
                'status'   => $response->status(),
                'body'     => $json,
                'message_preview' => mb_substr($message, 0, 100),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('[Fonnte] Exception during WA send.', [
                'target'    => $phoneNumber,
                'exception' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function buildBillingMessage(string $billingCode, string $formattedAmount, string $reservationId): string
    {
        $uploadUrl = route('customer.payment.show', $reservationId);

        return "*INSTRUKSI PEMBAYARAN PNBP*\n\n"
            . "Reservasi Anda telah kami terima. Silakan lakukan pembayaran melalui:\n\n"
            . "*Kode Billing SIMPONI:*\n"
            . "{$billingCode}\n\n"
            . "*Nominal Pembayaran:*\n"
            . "{$formattedAmount}\n\n"
            . "*Cara Bayar:*\n"
            . "1. Buka aplikasi bank / ATM / e-wallet Anda\n"
            . "2. Pilih menu PNBP / Pajak / MPN G3\n"
            . "3. Masukkan Kode Billing di atas\n"
            . "4. Konfirmasi dan selesaikan pembayaran\n"
            . "5. Simpan NTPN (16 digit) dari bukti pembayaran\n"
            . "6. Buka link berikut untuk upload NTPN dan bukti pembayaran:\n"
            . "{$uploadUrl}\n\n"
            . "Pembayaran wajib dilakukan dalam *72 jam*.\n"
            . "_Abaikan pesan ini jika Anda tidak merasa melakukan reservasi._";
    }
}
