<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Traits\ManageCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
  use ManageCache;

  const ALL_CACHE = 'UR';

  /**
   * Update the Authenticated User's username or email.
   */
  public function update(array $body): User
  {
    if (array_key_first($body) === 'username') {
      Auth::user()->username = $body['username'];
      Auth::user()->saveOrFail();
      $this->clearDependentCacheTags([TierListRepository::PUBLIC_CACHE]);
    }

    $isOauth = (bool) Auth::user()->getOAuthProvider();

    if (array_key_first($body) === 'email' && ! $isOauth) {
      Auth::user()->email = $body['email'];
      Auth::user()->email_verified_at = null;
      Auth::user()->saveOrFail();
      Auth::user()->sendEmailVerificationNotification();
    }

    return Auth::user();
  }

  public function changePassword(array $body): void
  {
    $newPassword = $body['password'];
    Auth::user()->password = Hash::make($newPassword);
    Auth::user()->saveOrFail();
  }
}
