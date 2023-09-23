<?php

namespace App\Repositories;

use App\Models\TierList;
use App\Models\User;
use App\Repositories\Traits\ManageCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TierListRepository
{
  use ManageCache;

  const ALL_CACHE = 'TLR';

  const RECENT_CACHE = 'TLR_R';

  public function store(array $validatedData)
  {
      dd($validatedData);
  }

  public function recent(): Collection
  {
    return Cache::tags([static::ALL_CACHE])->rememberForever(
      key: static::RECENT_CACHE,
      callback: fn () => TierList::select('id', 'title', 'description', 'thumbnail', Model::CREATED_AT, User::FOREIGN_KEY)
        ->whereIsPublic()
        ->orderByRecency()
        ->with('creator:id,username')
        ->take(4)
        ->get()
        ->makeHidden(User::FOREIGN_KEY)
    );
  }
}
