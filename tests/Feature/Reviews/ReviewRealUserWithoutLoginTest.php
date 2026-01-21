<?php

namespace Tests\Feature\Reviews;

use Tests\TestCase;
use App\Models\User;

class ReviewRealUserWithoutLoginTest extends TestCase
{
    // SIN DatabaseTransactions - trabaja con BD real

    public function test_usuario_real_puede_crear_review_de_pelicula(): void
    {
        // Obtener usuario real de la BD
        $user = User::find(1);

        // Crear review usando actingAs (sin login)
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/reviews', [
                'user_id' => $user->id,
                'tmdb_id' => 999001,
                'title' => 'Test Movie Real User',
                'media_type' => 'movie',
                'rating' => 5,
                'review' => 'Review de prueba con usuario real',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'review' => [
                    'id',
                    'user_id',
                    'tmdb_id',
                    'title',
                    'media_type',
                    'rating',
                    'review',
                ]
            ]);

        $reviewId = $response->json('review.id');

        // Verificar que se guardó en BD
        $this->assertDatabaseHas('reviews', [
            'id' => $reviewId,
            'user_id' => 1,
            'tmdb_id' => 999001,
            'rating' => 5,
        ]);
    }


    public function test_dos_usuarios_reales_pueden_crear_reviews_para_misma_pelicula(): void
    {
        // Obtener usuarios reales de la BD
        $user1 = User::find(1);
        $user2 = User::find(23);

        // User1 crea su review
        $response1 = $this->actingAs($user1, 'sanctum')
            ->postJson('/api/reviews', [
                'user_id' => $user1->id,
                'tmdb_id' => 999002,
                'title' => 'Inception',
                'media_type' => 'movie',
                'rating' => 5,
                'review' => 'Obra maestra de Nolan - Usuario 1',
            ]);

        $response1->assertStatus(200);
        $reviewId1 = $response1->json('review.id');

        // User2 crea su review para la MISMA película
        $response2 = $this->actingAs($user2, 'sanctum')
            ->postJson('/api/reviews', [
                'user_id' => $user2->id,
                'tmdb_id' => 999002,
                'title' => 'Inception',
                'media_type' => 'movie',
                'rating' => 4,
                'review' => 'Muy buena, pero algo confusa - Usuario 2',
            ]);

        $response2->assertStatus(200);
        $reviewId2 = $response2->json('review.id');

        // Verificar que AMBAS existen en BD
        $this->assertDatabaseHas('reviews', [
            'id' => $reviewId1,
            'user_id' => 1,
            'tmdb_id' => 999002,
            'rating' => 5,
        ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $reviewId2,
            'user_id' => 23,
            'tmdb_id' => 999002,
            'rating' => 4,
        ]);

    }
}
