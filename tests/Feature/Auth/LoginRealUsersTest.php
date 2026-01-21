<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginRealUsersTest extends TestCase
{
    // SIN DatabaseTransactions - usa datos reales

    public function test_login_con_usuario_real_de_base_de_datos(): void
    {
        // Usa un email que YA EXISTE en tu BD
        $response = $this->postJson('/api/login', [
            'email' => 'yergiroska77@gmail.com', // ← Cambia esto
            'password' => '12345678', // ← Y esto
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'token'
        ]);

        $token = $response->json('token');
        $this->assertNotEmpty($token);

        // Extra: probar que el token funciona
        $profileResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $profileResponse->assertStatus(200);
    }

    public function test_login_falla_con_usuario_inexistente(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'noexiste@example.com',
            'password' => 'cualquierpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(value: [
            'message' => 'Las credenciales son incorrectas' // ← Valida el mensaje
        ]);
    }

    public function test_login_falla_con_email_no_valido(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'noexiste',
            'password' => 'cualquierpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJson(value: [
            'message' => 'El email debe ser una dirección válida' // ← Valida el mensaje
        ]);
    }
}
