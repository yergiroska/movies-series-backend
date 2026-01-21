<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mediaType = fake()->randomElement(['movie', 'tv']);

        // Títulos realistas según el tipo
        $movieTitles = [
            'The Shawshank Redemption',
            'The Godfather',
            'The Dark Knight',
            'Pulp Fiction',
            'Inception',
            'Fight Club',
            'Forrest Gump',
            'The Matrix',
            'Interstellar',
            'Parasite'
        ];

        $tvTitles = [
            'Breaking Bad',
            'Game of Thrones',
            'The Wire',
            'The Sopranos',
            'Stranger Things',
            'The Crown',
            'Better Call Saul',
            'Succession',
            'The Last of Us',
            'Wednesday'
        ];

        $title = $mediaType === 'movie'
            ? fake()->randomElement($movieTitles)
            : fake()->randomElement($tvTitles);

        // Comentarios variados y realistas
        $reviews = [
            'Una obra maestra del cine. Cada escena es perfecta.',
            'Me mantuvo al borde del asiento de principio a fin.',
            'Actuaciones increíbles. La cinematografía es espectacular.',
            'Una historia que te hace pensar días después de verla.',
            'Decepcionante comparado con las expectativas que tenía.',
            'Entretenida pero nada memorable.',
            'Los efectos visuales son impresionantes pero la historia flaquea.',
            'Una joya oculta que todo el mundo debería ver.',
            'Sobrevalorada. No entiendo el hype.',
            'Perfecta para ver en familia. Nos encantó.',
        ];

        return [
            'user_id' => User::factory(),
            'tmdb_id' => fake()->numberBetween(1000, 999999),
            'media_type' => $mediaType,
            'title' => $title,
            'poster_path' => fake()->boolean(80) ? '/poster_' . fake()->numberBetween(1, 100) . '.jpg' : null,
            'rating' => fake()->numberBetween(1, 5),
            'review' => fake()->boolean(70) ? fake()->randomElement($reviews) : null,
        ];
    }
}
