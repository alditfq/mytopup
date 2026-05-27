<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'code',
        'description',
        'discount_amount',
        'min_transaction',
        'discount_type',
        'expiry_date',
        'max_uses',
        'uses_count',
        'is_active',
        'claim_url'

    ];
}
