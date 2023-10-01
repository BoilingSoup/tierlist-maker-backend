<?php

namespace App\Repositories;

use App\Models\TierList;
use App\Models\User;
use App\Repositories\Traits\ManageCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TierListRepository
{
  use ManageCache;

  const ALL_CACHE = 'TLR';

  const RECENT_CACHE = 'TLR_R';

  public function getOrFail(string $uuid)
  {
    return Cache::tags([static::ALL_CACHE])->rememberForever(
      key: $uuid,
      callback: fn () => TierList::findOrFail($uuid)->makeHidden(User::FOREIGN_KEY)
    );
  }

  public function index()
  {
    //
  }

  public function store(array $validatedData)
  {
    $tierList = TierList::create([
        'title' => $validatedData['title'] ?? 'Untitled - '.now()->toDateTimeString(),
        'data' => json_encode($validatedData['data']),
        'thumbnail' => $validatedData['thumbnail'] ?? 'dummy',
        'description' => $validatedData['description'] ?? null,
        'is_public' => false,
        User::FOREIGN_KEY => Auth::user()->id,
    ])->makeHidden(User::FOREIGN_KEY);

    // TODO: flush user's cache

    return $tierList;
  }

  public function update(TierList $tierList, array $validatedData)
  {
    $tierList->update($validatedData);
    $tierList->save();

    Cache::forget($tierList->uuid);
    if ($tierList->is_public) {
      Cache::forget(static::RECENT_CACHE);
    }
    // flush user cache

    return $tierList;
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
