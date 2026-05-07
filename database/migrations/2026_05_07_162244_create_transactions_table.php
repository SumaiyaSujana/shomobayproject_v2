<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create wallet transaction records.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('group_cart_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();

            $table->integer('amount_paisa');
            $table->string('type');
            $table->string('status')->default('completed');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Drop transactions table.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};