<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'building_id',
        'start_date',
        'end_date',
        'status',
        'customer_data',
        'lock_expires_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date'      => 'date',
            'end_date'        => 'date',
            'status'          => ReservationStatus::class,
            'customer_data'   => 'array',
            'lock_expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getTotalAmountAttribute(): float
    {
        if (!$this->building || !$this->building->facilityType) {
            return 0;
        }
        return $this->building->facilityType->daily_rate * $this->duration_days;
    }

    public function getCustomerNameAttribute(): string
    {
        $data = $this->customer_data ?? [];
        // Coba cari field yang mengandung kata 'nama' atau 'name'
        foreach ($data as $key => $value) {
            if (stripos($key, 'nama') !== false || stripos($key, 'name') !== false) {
                if (!empty($value)) return $value;
            }
        }
        
        if ($this->user) {
            return $this->user->name;
        }
        
        return 'Tamu (Guest)';
    }

    public function getCustomerPhoneAttribute(): string
    {
        $data = $this->customer_data ?? [];
        foreach ($data as $key => $value) {
            if (stripos($key, 'hp') !== false || stripos($key, 'telp') !== false || stripos($key, 'phone') !== false || stripos($key, 'wa') !== false) {
                if (!empty($value)) return $value;
            }
        }
        
        if ($this->user) {
            return $this->user->phone_number ?? '-';
        }
        
        return '-';
    }

    public function scopePending($query)
    {
        return $query->where('status', ReservationStatus::PENDING_BILLING);
    }

    public function scopeVerifying($query)
    {
        return $query->where('status', ReservationStatus::VERIFYING);
    }

    /**
     * Check if this reservation overlaps with the given date range.
     */
    public static function hasConflict(string $buildingId, string $startDate, string $endDate, ?string $excludeId = null): bool
    {
        $query = static::where('building_id', $buildingId)
            ->whereNotIn('status', [ReservationStatus::REJECTED, ReservationStatus::EXPIRED])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
