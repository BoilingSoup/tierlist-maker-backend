<?php

namespace App\Repositories\Traits;

use Cache;

trait ManageCache
{
  public function clearDependentCacheKeys(array $cacheKeys)
  {
    foreach ($cacheKeys as $key) {
      Cache::forget($key);
    }
  }
}
