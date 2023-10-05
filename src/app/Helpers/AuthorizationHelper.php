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
    return $tierList->is_public || static::userOwnsTierList($tierList);
  }

  public static function canUpdateTierList(TierList $tierList): bool
  {
    return static::userOwnsTierList($tierList);
  }

  public static function canDeleteTierList(TierList $tierList): bool
  {
    return static::userOwnsTierList($tierList);
  }

  private static function userOwnsTierList(TierList $tierList): bool
  {
    return $tierList->user_id === Auth::user()?->id;
  }
}
