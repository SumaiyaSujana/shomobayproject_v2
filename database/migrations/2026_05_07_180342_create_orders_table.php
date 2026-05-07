<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create orders after a vendor bid is accepted.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_cart_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('bid_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('accepted_by_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('vendor_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->integer('item_amount_paisa');
            $table->integer('delivery_fee_paisa')->default(0);
            $table->integer('total_amount_paisa');

            $table->string('status')->default('escrow_held');

            $table->timestamps();
        });
    }

    /**
     * Drop orders table.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};