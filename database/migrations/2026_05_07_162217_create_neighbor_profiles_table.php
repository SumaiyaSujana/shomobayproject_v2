<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create neighbor profile details linked with users.
     */
    public function up(): void
    {
        Schema::create('neighbor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('apartment_building')->nullable();
            $table->string('location_coordinates')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Drop neighbor profiles table.
     */
    public function down(): void
    {
        Schema::dropIfExists('neighbor_profiles');
    }
};