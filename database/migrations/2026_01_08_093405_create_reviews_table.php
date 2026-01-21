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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('tmdb_id');
            $table->enum('media_type', ['movie', 'tv']);
            $table->string('title');
            $table->string('poster_path')->nullable();
            $table->integer('rating')->nullable(); // 1-5 estrellas
            $table->text('review')->nullable(); // La reseña escrita
            $table->timestamps();

            // Un usuario solo puede tener una reseña por contenido
            $table->unique(['user_id', 'tmdb_id', 'media_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
