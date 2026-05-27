<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'title',
        'slug',
        'description',
        'rank',
        'level',
        'skin_count',
        'login_method',
        'bind_status',
        'price',
        'images',
        'account_data',
        'status',
        'featured',
    ];

    protected $casts = [
        'images' => 'array',
        'account_data' => 'encrypted',
        'featured' => 'boolean',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
