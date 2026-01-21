<?php

namespace Tests\Feature\Reviews;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateReviewTest extends TestCase
{
    use DatabaseTransactions;

    public function test_usuario_autenticado_puede_crear_review(): void
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

        // 2. Datos del review
        $reviewData = [
            'tmdb_id' => 550,
            'title' => 'Fight Club',
            'media_type' => 'movie',
            'rating' => 5, // 5 estrellas
            'review' => 'Excelente película, una obra maestra del cine.',
        ];

        // 3. Crear review
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/reviews', $reviewData);

        // 4. Verificar respuesta exitosa
        $response->assertStatus(200); // o 200

        $response->assertJson(value: [
            'message' => 'Reseña guardada exitosamente'
        ]);
        // 5. Verificar que se guardó en BD
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'tmdb_id' => 550,
            'media_type' => 'movie',
            'rating' => 5,
        ]);

        // 6. Verificar estructura de respuesta
        $response->assertJsonStructure([
            'review' => [
                'id',
                'user_id',
                'tmdb_id',
                'media_type',
                'rating',
                'review',
            ]
        ]);
    }

    public function test_puede_obtener_todas_las_reviews_de_una_pelicula(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // 2. Usuario 1 crea review
        $this->actingAs($user1, 'sanctum')
            ->postJson('/api/reviews', [
                'tmdb_id' => 551,
                'title' => 'The Matrix',
                'media_type' => 'movie',
                'rating' => 5,
                'review' => 'Una película revolucionaria',
            ]);

        // 3. Usuario 2 crea review
        $this->actingAs($user2, 'sanctum')
            ->postJson('/api/reviews', [
                'tmdb_id' => 551,
                'title' => 'The Matrix',
                'media_type' => 'movie',
                'rating' => 4,
                'review' => 'Muy buena película',
            ]);

        // 4. Usuario 3 crea review
        $this->actingAs($user3, 'sanctum')
            ->postJson('/api/reviews', [
                'tmdb_id' => 551,
                'title' => 'The Matrix',
                'media_type' => 'movie',
                'rating' => 3,
                'review' => 'Está bien',
            ]);

        // 5. Obtener todas las reviews
        $response = $this->actingAs($user1, 'sanctum')
            ->getJson('/api/reviews/movie/551');

        $response->assertStatus(200);

        $reviews = $response->json('reviews');
        $this->assertCount(3, $reviews);
    }

}
