<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('game_account_id')->nullable()->after('nominal_id')->constrained('game_accounts')->onDelete('set null');
            
            // Drop foreign key constraints before changing the column schema
            $table->dropForeign(['nominal_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Modify nominal_id to be nullable
            $table->foreignId('nominal_id')->nullable()->change();
            
            // Restore foreign key constraint
            $table->foreign('nominal_id')->references('id')->on('nominals')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['game_account_id']);
            $table->dropColumn('game_account_id');
            
            $table->dropForeign(['nominal_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('nominal_id')->nullable(false)->change();
            $table->foreign('nominal_id')->references('id')->on('nominals')->onDelete('cascade');
        });
    }
};
