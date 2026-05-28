<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genre_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')
                ->constrained('genres')
                ->cascadeOnDelete();
            $table->foreignId('track_id')
                ->constrained('tracks')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_tracks');
    }
};
