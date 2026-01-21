<?php

namespace Tests\Feature\Reviews;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class CreateReviewRealTest extends TestCase
{
    // SIN DatabaseTransactions - guarda en BD real

    public function test_usuario_real_puede_obtener_todas_las_reviews_de_una_pelicula(): void
    {
        // 1. Login con usuario real
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'yergiroska77@gmail.com',
            'password' => '12345678',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');
        $userId = $loginResponse->json('user.id');

        // 2. Limpiar reviews de prueba anteriores si existen
        DB::table('reviews')
            ->where('user_id', $userId)
            ->whereIn('tmdb_id', [777771, 777772, 777773])
            ->delete();

        // 3. Crear 3 reviews de prueba con tmdb_ids únicos
        $review1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/reviews', [
            'tmdb_id' => 777771, // ID único para testing
            'title' => 'Test Movie 1',
            'media_type' => 'movie',
            'rating' => 5,
            'review' => 'Review de prueba 1',
        ]);

        $review1->assertStatus(200);

        $review2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/reviews', [
            'tmdb_id' => 777772,
            'title' => 'Test Movie 2',
            'media_type' => 'movie',
            'rating' => 4,
            'review' => 'Review de prueba 2',
        ]);

        $review2->assertStatus(200);

        $review3 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/reviews', [
            'tmdb_id' => 777773,
            'title' => 'Test Movie 3',
            'media_type' => 'movie',
            'rating' => 3,
            'review' => 'Review de prueba 3',
        ]);

        $review3->assertStatus(200);

        // 4. Verificar que se guardaron en BD
        $this->assertDatabaseHas('reviews', [
            'user_id' => $userId,
            'tmdb_id' => 777771,
        ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $userId,
            'tmdb_id' => 777772,
        ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $userId,
            'tmdb_id' => 777773,
        ]);

        // 5. Obtener los IDs de las reviews creadas
        $reviewId1 = $review1->json('review.id');
        $reviewId2 = $review2->json('review.id');
        $reviewId3 = $review3->json('review.id');

        // 6. Limpiar: eliminar las reviews de prueba
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/reviews/{$reviewId1}");

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/reviews/{$reviewId2}");

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/reviews/{$reviewId3}");

        // 7. Verificar que se eliminaron
        $this->assertDatabaseMissing('reviews', ['id' => $reviewId1]);
        $this->assertDatabaseMissing('reviews', ['id' => $reviewId2]);
        $this->assertDatabaseMissing('reviews', ['id' => $reviewId3]);

        echo "\n✅ Test completado: 3 reviews creadas y eliminadas correctamente\n";
    }

    public function test_dos_usuarios_reales_pueden_crear_reviews_para_misma_pelicula(): void
    {
        // 1. Login con usuario real 1
        $loginResponse1 = $this->postJson('/api/login', [
            'email' => 'yergiroska77@gmail.com',
            'password' => '12345678',
        ]);

        $loginResponse1->assertStatus(200);
        $token1 = $loginResponse1->json('token');
        $userId1 = $loginResponse1->json('user.id');

        // 2. Login con usuario real 2
        $loginResponse2 = $this->postJson('/api/login', [
            'email' => 'yergiroska66@gmail.com',
            'password' => '12345678', // ← Usa la contraseña correcta
        ]);

        $loginResponse2->assertStatus(200);
        $token2 = $loginResponse2->json('token');
        $userId2 = $loginResponse2->json('user.id');

        // 3. Verificar que son usuarios diferentes
        $this->assertNotEquals($userId1, $userId2, "Los usuarios deben ser diferentes");

        // 4. Limpiar reviews de prueba anteriores (por si ejecutaste el test antes)
        DB::table('reviews')
            ->where('tmdb_id', 888888)
            ->where('media_type', 'movie')
            ->whereIn('user_id', [$userId1, $userId2])
            ->delete();

        // 5. Usuario 1 crea su review
        $review1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
            'Accept' => 'application/json',
        ])->postJson('/api/reviews', [
            'user_id' => $userId1,
            'tmdb_id' => 888888,
            'title' => 'Inception',
            'media_type' => 'movie',
            'rating' => 5,
            'review' => 'Obra maestra de Nolan - Usuario 1',
        ]);

        $review1->assertStatus(200);

        // 6. Usuario 2 crea su review para la MISMA película
        $review2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token2,
            'Accept' => 'application/json',
        ])->postJson('/api/reviews', [
            'user_id' => $userId2,
            'tmdb_id' => 888888,
            'title' => 'Inception',
            'media_type' => 'movie',
            'rating' => 4,
            'review' => 'Muy buena, pero algo confusa - Usuario 2',
        ]);

        $review2->assertStatus(200);

        // 7. Verificar que AMBAS reviews existen en BD
        $this->assertDatabaseHas('reviews', [
            'user_id' => $userId1,
            'tmdb_id' => 888888,
            'rating' => 5,
        ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $userId2,
            'tmdb_id' => 888888,
            'rating' => 4,
        ]);

        // 8. Obtener todas las reviews de esa película
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
            'Accept' => 'application/json',
        ])->getJson('/api/reviews/movie/888888');

        $response->assertStatus(200);

        $reviews = $response->json('reviews');

        // Verificar que hay al menos 2 reviews
        $this->assertGreaterThanOrEqual(2, count($reviews));

        // Verificar que hay reviews de ambos usuarios
        $userIds = collect($reviews)->pluck('user_id')->unique();
        $this->assertTrue($userIds->contains($userId1));
        $this->assertTrue($userIds->contains($userId2));

        echo "\n✅ Test completado: 2 usuarios reales crearon reviews para Inception (ID: 888888)\n";
        echo "📝 Reviews creadas quedan guardadas en la BD\n";
    }
}
