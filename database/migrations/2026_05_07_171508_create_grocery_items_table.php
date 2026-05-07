<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create grocery items that neighbors can bulk-buy.
     */
    public function up(): void
    {
        Schema::create('grocery_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Normal market price per kg, stored in paisa.
            $table->integer('market_price_per_kg_paisa');

            // Best wholesale price per kg after reaching target threshold.
            $table->integer('wholesale_price_per_kg_paisa');

            // Minimum total group weight needed for wholesale checkout.
            $table->integer('minimum_bulk_weight_grams');

            // Minimum amount a neighbor must contribute to join.
            $table->integer('minimum_contribution_grams')->default(1000);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Drop grocery items table.
     */
    public function down(): void
    {
        Schema::dropIfExists('grocery_items');
    }
};