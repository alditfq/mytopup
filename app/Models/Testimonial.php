<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'avatar',
        'game_name',
        'message',
        'rating',
        'is_approved',
        'is_featured'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
