<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('code')->unique();
            $table->text('description');
            $table->integer('discount_amount');
            $table->integer('min_transaction');
            $table->string('discount_type')->default('nominal'); // 'nominal' or 'percent'
            $table->dateTime('expiry_date')->nullable();
            $table->integer('max_uses')->default(100);
            $table->integer('uses_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('claim_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
