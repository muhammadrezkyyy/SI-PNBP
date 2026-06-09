<?php

namespace App\Jobs;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationSweeperJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function handle(): void
    {
        Log::info('[Sweeper] Starting ReservationSweeperJob...');

        $affected = DB::transaction(function () {
            return Reservation::whereIn('status', [
                    ReservationStatus::PENDING_BILLING->value,
                    ReservationStatus::WAITING_PAYMENT->value,
                ])
                ->where('lock_expires_at', '<', now())
                ->lockForUpdate()
                ->update([
                    'status' => ReservationStatus::EXPIRED->value,
                ]);
        });

        Log::info("[Sweeper] Expired {$affected} stale reservation(s).");
    }
}
