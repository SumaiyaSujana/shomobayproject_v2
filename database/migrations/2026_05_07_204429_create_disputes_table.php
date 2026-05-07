<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create dispute records for delivered orders.
     */
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('group_cart_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('vendor_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('reason');
            $table->integer('refund_requested_paisa')->default(0);
            $table->string('status')->default('open');

            $table->foreignId('resolved_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('admin_note')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->unique(['order_id', 'user_id']);
        });
    }

    /**
     * Drop dispute records.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};