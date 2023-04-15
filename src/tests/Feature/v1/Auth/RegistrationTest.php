<?php

namespace Tests\Feature\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\V1\Traits\UserResourceJsonStructure;
use Tests\Feature\V1\Traits\UserWithEmailAlreadyExistsJsonStructure;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
  use UserWithEmailAlreadyExistsJsonStructure;
  use UserResourceJsonStructure;
  use RefreshDatabase;

  public function test_new_users_can_register(): void
  {
    $response = $this->attemptRegistration();

    $this->assertAuthenticated();
    $response->assertJsonStructure($this->userResourceJsonStructure());
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
