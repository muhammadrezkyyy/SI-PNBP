<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacilityType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'daily_rate',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
        ];
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function activeBuildings()
    {
        return $this->hasMany(Building::class)->where('is_active', true);
    }

    public function images()
    {
        return $this->hasMany(FacilityTypeImage::class)->orderBy('sort_order');
    }

    /**
     * Get all image paths: cover image + gallery images combined.
     */
    public function getAllImagePathsAttribute(): array
    {
        $paths = [];
        if ($this->image_path) {
            $paths[] = $this->image_path;
        }
        foreach ($this->images as $img) {
            $paths[] = $img->image_path;
        }
        return $paths;
    }

    public function getDailyRateFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->daily_rate, 0, ',', '.');
    }
}
