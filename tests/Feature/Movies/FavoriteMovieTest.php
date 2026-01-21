<?php

namespace Tests\Feature\Movies;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FavoriteMovieTest extends TestCase
{
   use DatabaseTransactions;

    public function test_usuario_autenticado_puede_agregar_pelicula_a_favoritos(): void
    {
        // 1. Crear usuario y hacer login
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('token');

        // 2. Datos de la película a agregar
        $movieData = [
            'tmdb_id' => 550, // Fight Club
            'title' => 'Fight Club',
            'poster_path' => '/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg',
            'overview' => 'A ticking-time-bomb insomniac...',
            'release_date' => '1999-10-15',
            'vote_average' => 8.4,
            'media_type' => 'movie',
        ];

        // 3. Agregar a favoritos
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/favorites', $movieData);

        // 4. Verificar respuesta exitosa
        $response->assertStatus(201); // o 200, según tu API

        // 5. Verificar que se guardó en la BD
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'tmdb_id' => 550,
            'media_type' => 'movie',
        ]);

        // 6. Verificar estructura de respuesta
        $response->assertJsonStructure([
            'message',
            'favorite' => [
                'id',
                'user_id',
                'tmdb_id',
                'title',
            ]
        ]);
    }

    public function test_usuario_puede_eliminar_favorito_por_id(): void
    {
        // 1. Crear usuario y hacer login
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('token');

        // 2. Primero crear un favorito
        $movieData = [
            'tmdb_id' => 550,
            'title' => 'Fight Club',
            'poster_path' => '/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg',
            'overview' => 'A ticking-time-bomb insomniac...',
            'release_date' => '1999-10-15',
            'vote_average' => 8.4,
            'media_type' => 'movie',
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/favorites', $movieData);

        $favoriteId = $createResponse->json('favorite.id');

        // 3. Verificar que existe en BD
        $this->assertDatabaseHas('favorites', [
            'id' => $favoriteId,
            'user_id' => $user->id,
        ]);

        // 4. Eliminar el favorito por ID
        $deleteResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/favorites/{$favoriteId}");

        // 5. Verificar respuesta exitosa
        $deleteResponse->assertStatus(200); // o 204, según tu API

        // 6. Verificar que ya NO existe en BD
        $this->assertDatabaseMissing('favorites', [
            'id' => $favoriteId,
        ]);
    }

    public function test_usuario_puede_eliminar_favorito_por_tmdb_id(): void
    {
        // 1. Crear usuario y hacer login
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('token');

        // 2. Crear un favorito
        $movieData = [
            'tmdb_id' => 551,
            'title' => 'The Matrix',
            'poster_path' => '/matrix.jpg',
            'overview' => 'A computer hacker learns...',
            'release_date' => '1999-03-31',
            'vote_average' => 8.7,
            'media_type' => 'movie',
        ];

        $createResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/favorites', $movieData);

        // 3. Verificar que existe en BD
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'tmdb_id' => 551,
            'media_type' => 'movie',
        ]);

        // 4. Eliminar el favorito por mediaType y tmdbId
        $deleteResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson('/api/favorites/movie/551');

        // 5. Verificar respuesta exitosa
        $deleteResponse->assertStatus(200); // o 204

        // 6. Verificar que ya NO existe en BD
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'tmdb_id' => 551,
            'media_type' => 'movie',
        ]);
    }
}
