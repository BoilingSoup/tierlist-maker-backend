<?php

namespace Tests\Feature\V1\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase;

  public function test_getOAuthProvider_method_returns_expected_value()
  {
    $user = User::factory()->create();
    $ret = $user->getOAuthProvider();
    $this->assertNull($ret);

    $user = User::factory()->github()->create();
    $ret = $user->getOAuthProvider();
    $this->assertEquals($ret, 'GITHUB');

    $user = User::factory()->gitlab()->create();
    $ret = $user->getOAuthProvider();
    $this->assertEquals($ret, 'GITLAB');

    $user = User::factory()->google()->create();
    $ret = $user->getOAuthProvider();
    $this->assertEquals($ret, 'GOOGLE');

    $user = User::factory()->reddit()->create();
    $ret = $user->getOAuthProvider();
    $this->assertEquals($ret, 'REDDIT');

    $user = User::factory()->discord()->create();
    $ret = $user->getOAuthProvider();
    $this->assertEquals($ret, 'DISCORD');
  }
}
