<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'category',
        'thumbnail_url',
        'banner_url',
        'rating',
        'total_sold',
        'developer',
        'id_label',
        'zone_id_label',
        'id_helper_text',
        'cashback_percent',
        'has_discount'
    ];

    public function nominals()
    {
        return $this->hasMany(Nominal::class);
    }
}
