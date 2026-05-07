<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create substitution requests proposed by vendors.
     */
    public function up(): void
    {
        Schema::create('substitution_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('group_cart_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('vendor_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('original_item_name');
            $table->string('substitute_item_name');
            $table->text('reason')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Drop substitution requests table.
     */
    public function down(): void
    {
        Schema::dropIfExists('substitution_requests');
    }
};