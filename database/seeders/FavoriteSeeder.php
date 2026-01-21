<?php

namespace Database\Seeders;

use App\Models\Favorite;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // Usuario Yergiroska

        $favorites = [
            // Películas populares
            [
                'user_id' => $userId,
                'tmdb_id' => 278,
                'media_type' => 'movie',
                'title' => 'The Shawshank Redemption',
                'poster_path' => '/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg',
                'overview' => 'Framed in the 1940s for the double murder of his wife and her lover, upstanding banker Andy Dufresne begins a new life at the Shawshank prison.',
            ],
            [
                'user_id' => $userId,
                'tmdb_id' => 238,
                'media_type' => 'movie',
                'title' => 'The Godfather',
                'poster_path' => '/3bhkrj58Vtu7enYsRolD1fZdja1.jpg',
                'overview' => 'Spanning the years 1945 to 1955, a chronicle of the fictional Italian-American Corleone crime family.',
            ],
            [
                'user_id' => $userId,
                'tmdb_id' => 550,
                'media_type' => 'movie',
                'title' => 'Fight Club',
                'poster_path' => '/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg',
                'overview' => 'A ticking-time-bomb insomniac and a slippery soap salesman channel primal male aggression into a shocking new form of therapy.',
            ],
            [
                'user_id' => $userId,
                'tmdb_id' => 155,
                'media_type' => 'movie',
                'title' => 'The Dark Knight',
                'poster_path' => '/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'overview' => 'Batman raises the stakes in his war on crime with the help of Lt. Jim Gordon and District Attorney Harvey Dent.',
            ],
            [
                'user_id' => $userId,
                'tmdb_id' => 27205,
                'media_type' => 'movie',
                'title' => 'Inception',
                'poster_path' => '/ljsZTbVsrQSqZgWeep2B1QiDKuh.jpg',
                'overview' => 'Cobb, a skilled thief who commits corporate espionage by infiltrating the subconscious of his targets is offered a chance to regain his old life.',
            ],

            // Series populares
            [
                'user_id' => $userId,
                'tmdb_id' => 1396,
                'media_type' => 'tv',
                'title' => 'Breaking Bad',
                'poster_path' => '/ztkUQFLlC19CCMYHW9o1zWhJRNq.jpg',
                'overview' => 'A high school chemistry teacher diagnosed with inoperable lung cancer turns to manufacturing and selling methamphetamine.',
            ],
            [
                'user_id' => $userId,
                'tmdb_id' => 1399,
                'media_type' => 'tv',
                'title' => 'Game of Thrones',
                'poster_path' => '/1XS1oqL89opfnbLl8WnZY1O1uJx.jpg',
                'overview' => 'Seven noble families fight for control of the mythical land of Westeros.',
            ],
            [
                'user_id' => $userId,
                'tmdb_id' => 94605,
                'media_type' => 'tv',
                'title' => 'Arcane',
                'poster_path' => '/fqldf2t8ztc9aiwn3k6mlX3tvRT.jpg',
                'overview' => 'Amid the stark discord of twin cities Piltover and Zaun, two sisters fight on rival sides of a war between magic technologies and clashing convictions.',
            ],
            [
                'user_id' => $userId,
                'tmdb_id' => 85937,
                'media_type' => 'tv',
                'title' => 'Demon Slayer',
                'poster_path' => '/xUfRZu2mi8jH6SzQEJGP6tjBuYj.jpg',
                'overview' => 'A family is attacked by demons and only two members survive - Tanjiro and his sister Nezuko, who is turning into a demon slowly.',
            ],
            [
                'user_id' => $userId,
                'tmdb_id' => 46952,
                'media_type' => 'tv',
                'title' => 'The Mandalorian',
                'poster_path' => '/eU1i6eHXlzMOlEq0ku1Rzq7Y4wA.jpg',
                'overview' => 'After the fall of the Galactic Empire, lawlessness has spread throughout the galaxy. A lone gunfighter makes his way through the outer reaches.',
            ],
        ];

        foreach ($favorites as $favorite) {
            Favorite::create($favorite);
        }

        $this->command->info('✅ Favoritos seedeados correctamente para el usuario ID 1');
    }
}
