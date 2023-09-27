<?php

namespace App\Helpers;

use App\Models\TierList;
use Illuminate\Support\Facades\Auth;

/**
 * Some helper functions for authorization rules. I could use Gates/Policies but I don't like them.
 */
class AuthorizationHelper
{
  public static function canShowTierList(TierList $tierList): bool
  {
    return $tierList->is_public || Auth::user()?->id === $tierList->user_id;
  }
}
