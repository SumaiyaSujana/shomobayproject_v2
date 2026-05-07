<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Store how much each neighbor contributes to a group cart.
     */
    public function up(): void
    {
        Schema::create('cart_contributions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_cart_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('quantity_grams');

            // Price calculated at contribution time.
            $table->integer('estimated_amount_paisa');

            $table->timestamps();

            $table->unique(['group_cart_id', 'user_id']);
        });
    }

    /**
     * Drop cart contributions table.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_contributions');
    }
};