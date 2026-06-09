<?php

namespace App\Enums;

enum UserRole: string
{
    case CUSTOMER = 'CUSTOMER';
    case ADMIN    = 'ADMIN';

    public function label(): string
    {
        return match($this) {
            self::CUSTOMER => 'Pelanggan',
            self::ADMIN    => 'Administrator',
        };
    }
}
