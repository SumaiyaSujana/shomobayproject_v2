<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create neighborhood group carts.
     */
    public function up(): void
    {
        Schema::create('group_carts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('grocery_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('apartment_building');
            $table->string('location_coordinates')->nullable();

            $table->integer('target_weight_grams');
            $table->integer('current_weight_grams')->default(0);

            $table->timestamp('deadline_at');

            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Drop group carts table.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_carts');
    }
};