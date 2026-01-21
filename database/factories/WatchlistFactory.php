<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WatchlistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tmdb_id' => fake()->numberBetween(1000, 99999),
            'media_type' => fake()->randomElement(['movie', 'tv']),
            'title' => fake()->sentence(3),
            'poster_path' => '/fake/poster/path.jpg',
            'status' => fake()->randomElement(['watching', 'completed', 'plan_to_watch']),
            'user_rating' => fake()->optional()->randomFloat(1, 1, 5),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
