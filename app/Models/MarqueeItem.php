<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarqueeItem extends Model
{
    protected $fillable = ['text', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Return only active items, ordered by sort_order.
     */
    public static function activeItems()
    {
        return static::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
    }
}
