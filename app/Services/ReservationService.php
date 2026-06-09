<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ReservationService
{
    /**
     * Lock the schedule and create a reservation using pessimistic concurrency control.
     *
     * @throws ConflictHttpException When the building is already booked for the requested dates.
     */
    public function lockAndBook(
        string $buildingId,
        ?string $userId,
        string $startDate,
        string $endDate,
        array  $customerData
    ): Reservation {
        return DB::transaction(function () use ($buildingId, $userId, $startDate, $endDate, $customerData) {

            // Pessimistic lock: SELECT ... FOR UPDATE on conflicting rows.
            // This prevents any concurrent transaction from modifying these rows
            // until our transaction commits or rolls back.
            $conflict = Reservation::where('building_id', $buildingId)
                ->whereNotIn('status', [
                    ReservationStatus::REJECTED->value,
                    ReservationStatus::EXPIRED->value,
                ])
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q2) use ($startDate, $endDate) {
                          $q2->where('start_date', '<=', $startDate)
                             ->where('end_date', '>=', $endDate);
                      });
                })
                ->lockForUpdate()
                ->exists();

            if ($conflict) {
                throw new ConflictHttpException(
                    'Gedung tidak tersedia untuk tanggal yang dipilih. Silakan pilih tanggal lain.'
                );
            }

            return Reservation::create([
                'user_id'         => $userId,
                'building_id'     => $buildingId,
                'start_date'      => $startDate,
                'end_date'        => $endDate,
                'status'          => ReservationStatus::PENDING_BILLING,
                'customer_data'   => $customerData,
                'lock_expires_at' => now()->addHours(24),
            ]);
        });
    }

    /**
     * Transition a reservation to WAITING_PAYMENT after billing is uploaded.
     */
    public function transitionToWaitingPayment(Reservation $reservation): void
    {
        $reservation->update([
            'status'          => ReservationStatus::WAITING_PAYMENT,
            'lock_expires_at' => now()->addHours(72),
        ]);
    }

    /**
     * Transition a reservation to VERIFYING after customer submits NTPN.
     */
    public function transitionToVerifying(Reservation $reservation): void
    {
        $reservation->update([
            'status' => ReservationStatus::VERIFYING,
        ]);
    }

    /**
     * Confirm a reservation after admin approves audit.
     */
    public function confirm(Reservation $reservation): void
    {
        $reservation->update([
            'status'          => ReservationStatus::CONFIRMED,
            'lock_expires_at' => null,
        ]);
    }

    /**
     * Reject a reservation after admin rejects audit.
     */
    public function reject(Reservation $reservation): void
    {
        $reservation->update([
            'status'          => ReservationStatus::REJECTED,
            'lock_expires_at' => null,
        ]);
    }
}
