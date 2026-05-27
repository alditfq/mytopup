<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'group',
        'fee',
        'account_number',
        'instructions',
        'image'
    ];

    protected $casts = [
        'instructions' => 'array'
    ];
}
