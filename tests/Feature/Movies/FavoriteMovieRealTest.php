<?php

namespace Tests\Feature\Movies;

use Tests\TestCase;

class FavoriteMovieRealTest extends TestCase
{

    public function test_usuario_real_puede_agregar_pelicula_a_favoritos_en_bd(): void
    {
        $tmdbId = 551;
        // 1. Login con usuario real
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'yergiroska77@gmail.com',
            'password' => '12345678',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token);

        // 2. Limpiar favorito de prueba anterior si existe
        \DB::table('favorites')
            ->where('tmdb_id', $tmdbId)
            ->delete();

        // 3. Datos de la película a agregar
        $movieData = [
            'tmdb_id' => $tmdbId,
            'title' => 'Test Movie',
            'poster_path' => '/test-poster.jpg',
            'overview' => 'Esta es una película de prueba',
            'release_date' => '2024-01-01',
            'vote_average' => 7.5,
            'media_type' => 'movie',
        ];

        // 4. Agregar a favoritos
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/favorites', $movieData);

        // 5. Verificar respuesta exitosa
        $response->assertStatus(201);

        // 6. Verificar que se guardó REALMENTE en la BD
        $this->assertDatabaseHas('favorites', [
            'tmdb_id' => $tmdbId,
            'title' => 'Test Movie',
            'media_type' => 'movie',
        ]);

        // 7. Verificar estructura de respuesta
        $response->assertJsonStructure([
            'message',
            'favorite' => [
                'id',
                'user_id',
                'tmdb_id',
                'title',
            ]
        ]);

        /*$favoriteId = $response->json('favorite.id');

        // 8. Limpiar: eliminar el favorito de prueba
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/favorites/{$favoriteId}");

        // 9. Verificar que se eliminó
        $this->assertDatabaseMissing('favorites', [
            'id' => $favoriteId,
        ]);

        echo "\n✅ Test completado: Favorito creado y eliminado correctamente\n";*/
    }

    public function test_usuario_real_puede_eliminar_pelicula_a_favoritos_en_bd(): void
    {
        $favoriteId = 4;
        // 1. Login con usuario real
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'yergiroska77@gmail.com',
            'password' => '12345678',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token);

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

    public function test_usuario_real_puede_eliminar_favorito_por_tmdb_id(): void
    {
        $tmdbId = 551;
        // 1. Login con usuario real
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'yergiroska77@gmail.com',
            'password' => '12345678',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token);

        $token = $loginResponse->json('token');
        $userId = $loginResponse->json('user.id');

        // 4. Eliminar el favorito por mediaType y tmdbId
        $deleteResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/favorites/movie/{$tmdbId}");

        // 5. Verificar respuesta exitosa
        $deleteResponse->assertStatus(200); // o 204

        // 6. Verificar que ya NO existe en BD
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $userId,
            'tmdb_id' => $tmdbId,
            'media_type' => 'movie',
        ]);
    }

    public function test_usuario_real_puede_eliminar_pelicula_a_favoritos_en_no_existe_bd(): void
    {
        $favoriteId = 4;
        // 1. Login con usuario real
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'yergiroska77@gmail.com',
            'password' => '12345678',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token);

        $deleteResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/favorites/{$favoriteId}");

        // 5. Verificar respuesta exitosa
       $deleteResponse->assertStatus(404); // o 204, según tu API

        $deleteResponse->assertJson(value: [
            'message' => 'Favorito no encontrado'
        ]);

        // 6. Verificar que ya NO existe en BD
        $this->assertDatabaseMissing('favorites', [
            'id' => $favoriteId,
        ]);
    }
}
