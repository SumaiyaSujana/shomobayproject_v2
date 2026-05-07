<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add delivery coordinator discount fields to orders.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('delivery_coordinator_user_id')
                ->nullable()
                ->after('vendor_user_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->integer('coordinator_discount_paisa')
                ->default(0)
                ->after('total_amount_paisa');

            $table->timestamp('coordinator_selected_at')
                ->nullable()
                ->after('coordinator_discount_paisa');
        });
    }

    /**
     * Remove delivery coordinator discount fields.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_coordinator_user_id']);

            $table->dropColumn([
                'delivery_coordinator_user_id',
                'coordinator_discount_paisa',
                'coordinator_selected_at',
            ]);
        });
    }
};