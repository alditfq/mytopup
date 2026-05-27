<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'game_id',
        'reviewer_name',
        'rating',
        'message',
        'is_promoted',
    ];

    protected $casts = [
        'is_promoted' => 'boolean',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
