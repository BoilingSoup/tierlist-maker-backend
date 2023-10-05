<?php

namespace App\Repositories;

use App\Models\TierList;
use App\Models\User;
use App\Repositories\Traits\ManageCache;
use App\Services\ImageManagementService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TierListRepository
{
  use ManageCache;

  const ALL_CACHE = 'TLR';

  const RECENT_CACHE = 'TLR_R';

  public ImageManagementService $imageManagementService;

  public function __construct(ImageManagementService $imageManagementService)
  {
    $this->imageManagementService = $imageManagementService;
  }

  public function getOrFail(string $tierListID): TierList
  {
    return Cache::tags([static::ALL_CACHE])->rememberForever(
      key: $tierListID,
      callback: fn () => TierList::findOrFail($tierListID)->makeHidden(User::FOREIGN_KEY)
    );
  }

  public function getUserTierListsInfo(string $userID, string $cursor)
  {
    return Cache::tags([static::ALL_CACHE, $userID])->rememberForever(
      key: $userID.$cursor,
      callback: fn () => TierList::select('id', 'title', 'description', 'thumbnail', 'is_public', 'created_at', 'updated_at')->where(User::FOREIGN_KEY, $userID)->cursorPaginate()
    );
  }

  public function index()
  {
    //
  }

  public function store(array $validatedData)
  {
    $userID = Auth::user()->id;

    $tierList = TierList::create([
        'title' => $validatedData['title'] ?? 'Untitled - '.now()->toDateTimeString(),
        'data' => json_encode($validatedData['data']),
        'thumbnail' => $validatedData['thumbnail'] ?? 'dummy',
        'description' => $validatedData['description'] ?? null,
        'is_public' => false,
        User::FOREIGN_KEY => $userID,
    ])->makeHidden(User::FOREIGN_KEY);

    $this->flushUserTierListInfoCache($tierList->user_id);

    // TODO: if public, refresh recent

    return $tierList;
  }

  public function update(TierList $tierList, array $validatedData)
  {
    $tierList->update($validatedData);
    $tierList->save();

    $this->flushUserTierListInfoCache($tierList->user_id);
    $this->flushTierListCacheByID($tierList->id);
    if ($tierList->is_public) {
      Cache::tags([static::ALL_CACHE])->forget(static::RECENT_CACHE);
    }

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

  public function destroy(TierList $tierList)
  {
    // NOTE: when a user clones a public tierlist, they must reupload the images to cloudinary for consistency.
    TierList::destroy($tierList->id);

    // always flush user, and individual GET id
    $this->flushUserTierListInfoCache($tierList->user_id);
    $this->flushTierListCacheByID($tierList->id);

    // if public flush index & recent
    if ($tierList->is_public) {
      Cache::tags([static::ALL_CACHE])->forget(static::RECENT_CACHE);
    }

  }

  public function flushUserTierListInfoCache(string $userID)
  {
    Cache::tags([$userID])->flush();
  }

  public function flushTierListCacheByID(string $tierListID)
  {
    Cache::tags([static::ALL_CACHE])->forget($tierListID);
  }
}
