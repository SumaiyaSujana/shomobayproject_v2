<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create neighbor votes for substitution requests.
     */
    public function up(): void
    {
        Schema::create('substitution_votes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('substitution_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('vote');

            $table->timestamps();

            $table->unique(['substitution_request_id', 'user_id']);
        });
    }

    /**
     * Drop substitution votes table.
     */
    public function down(): void
    {
        Schema::dropIfExists('substitution_votes');
    }
};