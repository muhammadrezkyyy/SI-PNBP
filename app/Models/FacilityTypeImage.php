<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacilityTypeImage extends Model
{
    protected $fillable = [
        'facility_type_id',
        'image_path',
        'sort_order',
    ];

    public function facilityType(): BelongsTo
    {
        return $this->belongsTo(FacilityType::class);
    }
}
