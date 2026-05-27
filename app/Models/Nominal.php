<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nominal extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'item_id',
        'name',
        'price',
        'discount_price',
        'is_best_seller',
        'tag'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
