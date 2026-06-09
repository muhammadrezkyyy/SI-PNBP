<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'reservation_id',
        'simponi_billing_code',
        'nominal',
        'ntpn',
        'ntb',
        'receipt_path',
        'ocr_metadata',
        'simponi_pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'nominal'      => 'decimal:2',
            'ocr_metadata' => 'array',
        ];
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function auditLog(): HasOne
    {
        return $this->hasOne(AuditLog::class);
    }

    public function getNominalFormattedAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->nominal, 0, ',', '.');
    }
}
