<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
   // use RefreshDatabase;
    use DatabaseTransactions;

    public function testUnUsuarioPuedeRegistrarse(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Juan Pérez',
            'email' => 'juan@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'token'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'juan@test.com',
        ]);
    }

    public function testElEmailDebeSerUnico(): void
    {
        User::factory()->create(['email' => 'juan@test.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Pedro López',
            'email' => 'juan@test.com', // Email duplicado
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
