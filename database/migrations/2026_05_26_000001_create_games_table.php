<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('category');
            $table->string('thumbnail_url');
            $table->string('banner_url');
            $table->float('rating')->default(5.0);
            $table->string('total_sold')->default('0');
            $table->string('developer');
            $table->string('id_label');
            $table->string('zone_id_label')->nullable();
            $table->text('id_helper_text');
            $table->integer('cashback_percent')->default(0);
            $table->boolean('has_discount')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
