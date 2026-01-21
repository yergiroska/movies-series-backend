<?php

namespace Tests\Feature\CiveVerse\Reviews;

use App\Http\Controllers\Api\CineVerseReviewController;
use App\Http\Controllers\Api\ReviewController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class UpdateReviewTest extends TestCase
{

    public function test_add_review_movie_controller(): void
    {
        $user = User::find(120);
        $this->actingAs($user);

        $request = Request::create('/api/cineverse/movies/reviews/', 'POST', [
            "user_id" => $user->id,
            "tmdb_id" => 550,
            "title" => "Fight Club",
            "rating" => 4,
            "review" => "Excelente película",
        ]);

        $controller = new CineVerseReviewController();
        $response = $controller->store( $request);

        $this->assertEquals(200, $response->status());
    }

    public function test_usuario_puede_crear_review_movie_de_pelicula(): void
    {
        $user = User::find(120);

        $ruta = route(
            'cineverse.reviews.store',
            ['media_type' => 'movie']
        );
        // Crear review usando actingAs
        $response = $this->actingAs($user, 'sanctum');

        $response = $response->postJson($ruta, [
                'user_id' => $user->id,
                'tmdb_id' => 550,
                'title' => 'Fight Club',
                'rating' => 5,
                'review' => 'Excelente increíble, de las mejores que he visto',
            ]);

        $response->assertStatus(200);

    }
}
