<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create vendor bids for threshold-met group carts.
     */
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_cart_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('vendor_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->integer('price_per_kg_paisa');
            $table->integer('delivery_fee_paisa')->default(0);
            $table->timestamp('estimated_delivery_at')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('pending');

            $table->timestamps();

            $table->unique(['group_cart_id', 'vendor_user_id']);
        });
    }

    /**
     * Drop vendor bids table.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};