<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key, fallback to default.
     */
    public static function getVal(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set/Update a setting key-value pair.
     */
    public static function setVal(string $key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
