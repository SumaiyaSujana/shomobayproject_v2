<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add claim token fields for delivery pickup verification.
     */
    public function up(): void
    {
        Schema::table('cart_contributions', function (Blueprint $table) {
            $table->string('qr_claim_token')->nullable()->unique()->after('estimated_amount_paisa');
            $table->timestamp('claimed_at')->nullable()->after('qr_claim_token');
        });
    }

    /**
     * Remove claim token fields.
     */
    public function down(): void
    {
        Schema::table('cart_contributions', function (Blueprint $table) {
            $table->dropColumn([
                'qr_claim_token',
                'claimed_at',
            ]);
        });
    }
};