<?php

namespace App\Repositories\Traits;

use Cache;

trait ManageCache
{
  public function clearDependentCacheTags(array $cacheTags)
  {
    foreach ($cacheTags as $tags) {
      Cache::tags([$tags])->flush();
    }
  }
}
