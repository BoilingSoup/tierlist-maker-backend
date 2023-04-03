<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\UserPublicInfoJsonStructure;
use Tests\Feature\Traits\UserWithEmailAlreadyExistsJsonStructure;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use UserWithEmailAlreadyExistsJsonStructure;
    use UserPublicInfoJsonStructure;
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->attemptRegistration();

        $this->assertAuthenticated();
        $response->assertJsonStructure($this->userPublicInfoJsonStructure());
    }

    public function test_users_cant_register_with_same_email()
    {
        $this->attemptRegistration();
        $this->post('/logout');

        $this->attemptRegistration()
          ->assertStatus(403)
          ->assertJson($this->userWithEmailAlreadyExistsJsonStructure());

        $this->assertGuest();
    }

    private function attemptRegistration()
    {
        return $this->postJson('/register', [
            'username' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    }
}
