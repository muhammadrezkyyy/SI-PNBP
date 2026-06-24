<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Carbon\Carbon;
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
     * Pelanggan wajib membayar dalam 24 jam.
     */
    public function transitionToWaitingPayment(Reservation $reservation): void
    {
        $reservation->update([
            'status'          => ReservationStatus::WAITING_PAYMENT,
            'lock_expires_at' => now()->addHours(24),
        ]);
    }

    /**
     * Transition a reservation to VERIFYING after customer submits NTPN.
     *
     * Deadline admin dihitung berdasarkan HARI KERJA (Senin–Jumat):
     * - Upload Senin s/d Kamis → deadline 3 hari kerja berikutnya
     * - Upload Jumat           → deadline Rabu (skip Sabtu & Minggu)
     * - Upload Sabtu/Minggu    → deadline dihitung mulai Senin berikutnya
     *
     * Ini mencegah reservasi expired saat admin libur akhir pekan.
     */
    public function transitionToVerifying(Reservation $reservation): void
    {
        $deadline = $this->addWorkingDays(now(), 3);

        $reservation->update([
            'status'          => ReservationStatus::VERIFYING,
            'lock_expires_at' => $deadline,
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

    /**
     * Hitung tanggal deadline dengan menambahkan N hari kerja (skip Sabtu & Minggu).
     * Jika titik mulai adalah hari weekend, mulai dihitung dari Senin berikutnya.
     *
     * Contoh:
     *   Senin  + 3 hari kerja = Kamis
     *   Kamis  + 3 hari kerja = Selasa
     *   Jumat  + 3 hari kerja = Rabu   (skip Sabtu & Minggu)
     *   Sabtu  + 3 hari kerja = Rabu   (mulai hitung dari Senin)
     *   Minggu + 3 hari kerja = Rabu   (mulai hitung dari Senin)
     */
    private function addWorkingDays(Carbon $from, int $days): Carbon
    {
        $date = $from->copy();

        // Jika mulai dari weekend, lompat ke Senin pagi jam 08:00
        if ($date->isWeekend()) {
            $date->next(Carbon::MONDAY)->setTime(8, 0, 0);
        }

        $added = 0;
        while ($added < $days) {
            $date->addDay();
            if (!$date->isWeekend()) {
                $added++;
            }
        }

        // Batas akhir pukul 17:00 (jam kantor selesai)
        return $date->setTime(17, 0, 0);
    }
}
