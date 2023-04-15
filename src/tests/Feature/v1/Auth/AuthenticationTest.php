<?php

namespace Tests\Feature\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\V1\Traits\UserResourceJsonStructure;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
  use UserResourceJsonStructure;
  use RefreshDatabase;

  public function test_users_can_authenticate_using_the_login_screen(): void
  {
    $user = User::factory()->create();

    $response = $this->postJson('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertJsonStructure($this->userResourceJsonStructure());
  }

  public function test_users_can_not_authenticate_with_invalid_password(): void
  {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
  }
}
