<?php

namespace Tests\Feature\Reviews;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewWithoutLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_usuario_puede_crear_review_de_pelicula(): void
    {
        // Crear usuario temporal
        $user = User::factory()->create();

        // Crear review usando actingAs
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/reviews', [
                'user_id' => $user->id,
                'tmdb_id' => 550,
                'title' => 'Fight Club',
                'media_type' => 'movie',
                'rating' => 5,
                'review' => 'Película increíble, de las mejores que he visto',
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
                    'created_at',
                    'updated_at'
                ]
            ]);

        // Verificar que se guardó en BD
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'tmdb_id' => 550,
            'rating' => 5,
        ]);
    }


    public function test_dos_usuarios_pueden_crear_reviews_para_misma_pelicula(): void
    {
        // Crear dos usuarios temporales
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User1 crea su review
        $response1 = $this->actingAs($user1, 'sanctum')
            ->postJson('/api/reviews', [
                'user_id' => $user1->id,
                'tmdb_id' => 550,
                'title' => 'Fight Club',
                'media_type' => 'movie',
                'rating' => 5,
                'review' => 'Obra maestra - Usuario 1',
            ]);

        $response1->assertStatus(200);

        // User2 crea su review para la MISMA película
        $response2 = $this->actingAs($user2, 'sanctum')
            ->postJson('/api/reviews', [
                'user_id' => $user2->id,
                'tmdb_id' => 550,
                'title' => 'Fight Club',
                'media_type' => 'movie',
                'rating' => 3,
                'review' => 'Sobrevalorada - Usuario 2',
            ]);

        $response2->assertStatus(200);

        // Verificar que AMBAS se crearon en BD
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user1->id,
            'tmdb_id' => 550,
            'rating' => 5,
        ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user2->id,
            'tmdb_id' => 550,
            'rating' => 3,
        ]);
    }
}
