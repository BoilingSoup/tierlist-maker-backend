<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Traits\ManageCache;
use Illuminate\Support\Facades\Auth;

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
      $this->clearDependentCacheKeys([TierListRepository::RECENT_CACHE]);
    }

    $isOauth = (bool) Auth::user()->oauth_provider;

    if (array_key_first($body) === 'email' && $isOauth) {
      abort(403);
    } elseif (array_key_first($body) === 'email' && ! $isOauth) {
      Auth::user()->email = $body['email'];
      Auth::user()->email_verified_at = null;
      Auth::user()->sendEmailVerificationNotification();
      Auth::user()->saveOrFail();
    }

    return Auth::user();
  }
}
