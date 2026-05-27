<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->foreignId('game_id')->constrained('games');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nickname');
            $table->string('target_id');
            $table->string('zone_id')->nullable();
            $table->foreignId('nominal_id')->constrained('nominals');
            $table->string('nominal_name');
            $table->integer('nominal_price');
            $table->integer('discount_applied')->default(0);
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->integer('total_payment');
            $table->string('status')->default('pending'); // pending, success, failed
            $table->json('status_logs'); // stored JSON array
            $table->text('qr_code_url')->nullable();
            $table->string('va_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
