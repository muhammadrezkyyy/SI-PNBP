<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Helper to get a setting value.
     */
    public static function getVal($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Helper to set a setting value.
     */
    public static function setVal($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
