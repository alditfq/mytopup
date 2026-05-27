<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice',
        'game_id',
        'user_id',
        'nickname',
        'target_id',
        'zone_id',
        'nominal_id',
        'nominal_name',
        'nominal_price',
        'discount_applied',
        'payment_method_id',
        'total_payment',
        'status',
        'status_logs',
        'qr_code_url',
        'va_number'
    ];

    protected $casts = [
        'status_logs' => 'array'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nominal()
    {
        return $this->belongsTo(Nominal::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
