<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create wallets for users.
     *
     * Money is stored as paisa instead of decimal taka.
     * Example: 100 taka = 10000 paisa.
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->integer('balance_paisa')->default(0);
            $table->integer('escrow_paisa')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Drop wallets table.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};