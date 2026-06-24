<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case PENDING_BILLING  = 'PENDING_BILLING';
    case WAITING_PAYMENT  = 'WAITING_PAYMENT';
    case VERIFYING        = 'VERIFYING';
    case CONFIRMED        = 'CONFIRMED';
    case REJECTED         = 'REJECTED';
    case EXPIRED          = 'EXPIRED';
    case COMPLETED        = 'COMPLETED';

    public function label(): string
    {
        return match($this) {
            self::PENDING_BILLING => 'Menunggu Tagihan',
            self::WAITING_PAYMENT => 'Menunggu Pembayaran',
            self::VERIFYING       => 'Verifikasi',
            self::CONFIRMED       => 'Dikonfirmasi',
            self::REJECTED        => 'Ditolak',
            self::EXPIRED         => 'Kedaluwarsa',
            self::COMPLETED       => 'Selesai',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING_BILLING => 'yellow',
            self::WAITING_PAYMENT => 'orange',
            self::VERIFYING       => 'blue',
            self::CONFIRMED       => 'green',
            self::REJECTED        => 'red',
            self::EXPIRED         => 'gray',
            self::COMPLETED       => 'teal',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING_BILLING => 'badge-warning',
            self::WAITING_PAYMENT => 'badge-orange',
            self::VERIFYING       => 'badge-info',
            self::CONFIRMED       => 'badge-success',
            self::REJECTED        => 'badge-danger',
            self::EXPIRED         => 'badge-secondary',
            self::COMPLETED       => 'badge-info', // Or any custom class they have
        };
    }

    /** Returns statuses eligible for sweeper expiry. */
    public static function sweepable(): array
    {
        return [self::PENDING_BILLING, self::WAITING_PAYMENT];
    }
}
