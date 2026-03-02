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
        Schema::create('watchlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('tmdb_id');
            $table->enum('media_type', ['movie', 'tv']);
            $table->enum('status', ['watching', 'completed', 'plan_to_watch'])->default('plan_to_watch');
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['user_id', 'tmdb_id', 'media_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlists');
    }
};
