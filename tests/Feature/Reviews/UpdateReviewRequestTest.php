<?php

namespace Reviews;

use App\Http\Controllers\Api\ReviewController;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

class UpdateReviewRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use DatabaseTransactions; // Hace rollback automático después del test

    public function test_endpoint_actualiza_rating_resena_correctamente_sin_persistir()
    {
        // Buscar el usuario real
        $user = User::find(120);

        // Simular autenticación
        $this->actingAs($user);

        // Preparar request
        $request = Request::create('', 'PUT', [
            'rating' => 5
        ]);

        $id = 120;

        $controller = new ReviewController();
        $response = $controller->update($request, $id);

        // Assert - Solo validar respuesta
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Reseña actualizada exitosamente', $response->getData()->message);
        $this->assertEquals(5, $response->getData()->review->rating);
    }

    public function test_endpoint_actualiza_review_resena_correctamente_sin_persistir()
    {
        // Buscar el usuario real
        $user = User::find(120);

        // Simular autenticación
        $this->actingAs($user);


        $reviewText = 'TEST TEST';
        $request = Request::create('', 'PUT', [
            'review' => $reviewText,
        ]);

        $id = 120;

        $controller = new ReviewController();
        $response = $controller->update($request, $id);

        // Assert - Solo validar respuesta
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Reseña actualizada exitosamente', $response->getData()->message);
        $this->assertEquals($reviewText, $response->getData()->review->review);
    }

    public function test_endpoint_actualiza_review_rating_resena_correctamente_sin_persistir()
    {
        // Buscar el usuario real
        $user = User::find(120);

        // Simular autenticación
        $this->actingAs($user);


        $reviewText = 'TEST TEST';
        $ratingText = 5;
        $request = Request::create('', 'PUT', [
            'review' => $reviewText,
            'rating' => $ratingText,
        ]);

        $id = 120;

        $controller = new ReviewController();
        $response = $controller->update($request, $id);

        // Assert - Solo validar respuesta
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Reseña actualizada exitosamente', $response->getData()->message);
        $this->assertEquals($reviewText, $response->getData()->review->review);
        $this->assertEquals($ratingText, $response->getData()->review->rating);
    }

}
