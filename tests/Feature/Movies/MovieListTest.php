<?php

namespace Tests\Feature\Movies;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MovieListTest extends TestCase
{
    use DatabaseTransactions;

    public function test_usuario_autenticado_puede_obtener_listado_de_peliculas(): void
    {
        // 1. Crear un usuario
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 2. Hacer login para obtener el token
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('token');

        // 3. Usar el token para obtener películas
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson('/api/movies/popular');

        // 4. Verificar que funcionó
        $response->assertStatus(200);

        // ← AÑADE ESTO para verificar la estructura
        $response->assertJsonStructure([
            'results' => [ // o 'data', según tu API
                '*' => [
                    'id',
                    'title',
                ]
            ]
        ]);

        // Verificar que hay películas
        $this->assertNotEmpty($response->json('results'));
    }

    public function test_usuario_real_puede_obtener_peliculas(): void
    {

        // 2. Hacer login para obtener el token
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'yergiroska77@gmail.com',
            'password' => '12345678',
        ]);

        $token = $loginResponse->json('token');

        // 3. Usar el token para obtener películas
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson('/api/movies/popular');

        // 4. Verificar que funcionó
        $response->assertStatus(200);

        // ← AÑADE ESTO para verificar la estructura
        $response->assertJsonStructure([
            'results' => [ // o 'data', según tu API
                '*' => [
                    'id',
                    'title',
                ]
            ]
        ]);

        // Verificar que hay películas
        $this->assertNotEmpty($response->json('results'));
    }
}
