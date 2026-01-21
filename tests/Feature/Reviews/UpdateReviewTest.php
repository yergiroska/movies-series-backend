<?php

namespace Reviews;

use App\Http\Controllers\Api\ReviewController;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class UpdateReviewTest extends TestCase
{

    public function test_update_review_model_rating_with_user_id(): void
    {
        $id = 120;
        $ratingUpdate = 3;
        $review = Review::find($id);
        $review->rating = $ratingUpdate;

        $review->save();
        $this->assertEquals($ratingUpdate, $review->rating, 'El rating de la review no se ha actualizado correctamente');
    }
    /**
     * A basic feature test example.
     */
    public function test_update_review_model_resena_with_user_id(): void
    {
        $id = 120;
        $textUpdate = 'Update review MODEL';
        $review = Review::find($id);
        $review->review = $textUpdate;

        $review->save();
        $this->assertEquals($textUpdate, $review->review, 'El texto de la review no se ha actualizado correctamente');
    }

    public function test_puede_actualizar_solo_el_rating_de_una_resena()
    {
        // Arrange
        $user = User::find(120);

        // Simular autenticación
        $this->actingAs($user);

        // Preparar request
        $request = Request::create('', 'PATCH', [
            'rating' => 5
        ]);

        $id = 120;

        $controller = new ReviewController();
        $response = $controller->update($request, $id);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Reseña actualizada exitosamente', $response->getData()->message);
    }

    public function test_puede_actualizar_solo_el_resena_de_una_resena()
    {
        // Arrange
        $user = User::find(120);

        // Simular autenticación
        $this->actingAs($user);

        // Preparar request
        $request = Request::create('', 'PATCH', [
            'review' => 'TEST TEST',
        ]);

        $id = 120;

        $controller = new ReviewController();
        $response = $controller->update($request, $id);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Reseña actualizada exitosamente', $response->getData()->message);
    }

    public function test_puede_actualizar_resena_rating_de_una_resena()
    {
        // Arrange
        $user = User::find(120);

        // Simular autenticación
        $this->actingAs($user);

        // Preparar request
        $request = Request::create('', 'PATCH', [
            'review' => 'TEST TEST RESEÑA RATING',
            'rating' => 4,
        ]);

        $id = 120;

        $controller = new ReviewController();
        $response = $controller->update($request, $id);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Reseña actualizada exitosamente', $response->getData()->message);
    }

    public function test_puede_actualizar_solo_el_rating_review_blank_de_una_resena()
    {
        // Arrange
        $user = User::find(1);

        // Simular autenticación
        $this->actingAs($user);

        // Preparar request
        $request = Request::create('', 'PUT', [
            'rating' => 5,
            'review' => null
        ]);

        $id = 121;

        $controller = new ReviewController();
        $response = $controller->update($request, $id);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Reseña actualizada exitosamente', $response->getData()->message);
    }

    public function test_puede_actualizar_solo_el_review_rating_blank_de_una_resena()
    {
        // Arrange
        $user = User::find(120);

        // Simular autenticación
        $this->actingAs($user);

        // Preparar request
        $request = Request::create('', 'PUT', [
            'rating' => '',
            'review' => 'TEST RATING BLANCO'
        ]);

        $id = 120;

        $controller = new ReviewController();
        $response = $controller->update($request, $id);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Reseña actualizada exitosamente', $response->getData()->message);
    }

}
