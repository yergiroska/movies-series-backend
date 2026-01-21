<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 4) {
            echo "✗ Error: Necesitas al menos 4 usuarios.\n";
            return;
        }

        // Datos de la película específica
        $movieData = [
            'tmdb_id' => 1084242,
            'media_type' => 'movie',
            'title' => 'Zootrópolis 2',
            'poster_path' => '/inception_poster.jpg',
        ];

        // El Factory genera ratings y reviews automáticamente
        for ($i = 0; $i < 4; $i++) {
            Review::factory()->create(array_merge($movieData, [
                'user_id' => $users[$i]->id,
            ]));
        }

        echo "✓ Se crearon 4 reviews para '{$movieData['title']}'\n";
    }
}
