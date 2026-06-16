<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Carbon\Carbon;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set reservations older than 24 hours to EXPIRED if payment is not completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Temukan reservasi yang statusnya PENDING_BILLING atau WAITING_PAYMENT
        // dan sudah melewati 24 jam sejak dibuat (atau sejak WAITING_PAYMENT tergantung kebutuhan).
        // Kita gunakan 24 jam sejak created_at.
        
        $expiredLimit = Carbon::now()->subHours(24);
        
        $reservations = Reservation::whereIn('status', [ReservationStatus::PENDING_BILLING, ReservationStatus::WAITING_PAYMENT])
            ->where('created_at', '<', $expiredLimit)
            ->get();
            
        $count = 0;
        foreach ($reservations as $reservation) {
            $reservation->update([
                'status' => ReservationStatus::EXPIRED
            ]);
            $count++;
            
            // Opsional: Kirim notifikasi WhatsApp ke pelanggan bahwa pesanannya hangus
            // $fonnte = app(\App\Services\FonnteNotificationService::class);
            // ...
        }
        
        $this->info("Expired $count reservations.");
    }
}
