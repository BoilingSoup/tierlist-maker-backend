<?php

namespace App\Repositories;

use App\Models\TierList;
use App\Models\User;
use App\Repositories\Traits\ManageCache;
use App\Services\ImageManagementService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TierListRepository
{
  use ManageCache;

  const ALL_CACHE = 'TLR';

  const PUBLIC_CACHE = 'TLR_P';

  const RECENT_CACHE = 'TLR_R';

  const INDEX_CACHE = 'TLR_I';

  public ImageManagementService $imageManagementService;

  public function __construct(ImageManagementService $imageManagementService)
  {
    $this->imageManagementService = $imageManagementService;
  }

  public function index()
  {
    return Cache::tags([static::ALL_CACHE, static::PUBLIC_CACHE])->rememberForever(
      key: static::INDEX_CACHE,
      callback: fn () => TierList::select('id', 'title', 'description', 'thumbnail', 'is_public', Model::CREATED_AT, Model::UPDATED_AT, User::FOREIGN_KEY)
        ->whereIsPublic()
        ->orderByRecency()
        ->cursorPaginate(perPage: 12)
    );
  }

  public function getOrFail(string $tierListID): TierList
  {
    return Cache::tags([static::ALL_CACHE])->rememberForever(
      key: $tierListID,
      callback: fn () => TierList::findOrFail($tierListID)
    );
  }

  public function getUserTierListsInfo(string $userID, string $cursor)
  {
    return Cache::tags([static::ALL_CACHE, $userID])->rememberForever(
      key: $userID.$cursor,
      callback: fn () => TierList::select('id', 'title', 'description', 'thumbnail', 'is_public', Model::CREATED_AT, Model::UPDATED_AT, User::FOREIGN_KEY)
        ->where(User::FOREIGN_KEY, $userID)
        ->orderByRecency()
        ->cursorPaginate(perPage: 12)
    );
  }

  public function store(array $validatedData)
  {
    $userID = Auth::user()->id;

    $tierList = TierList::create([
        'title' => $validatedData['title'] ?? 'Untitled - '.now()->toDateTimeString(),
        'data' => json_encode($validatedData['data']),
        'thumbnail' => $validatedData['thumbnail'],
        'description' => $validatedData['description'] ?? null,
        'is_public' => $validatedData['is_public'],
        User::FOREIGN_KEY => $userID,
    ]);

    $this->flushUserTierListInfoCache($tierList->user_id);

    if ($tierList->is_public) {
      $this->flushAllPublicCache();
    }

    return $tierList;
  }

  public function update(TierList $tierList, array $validatedData)
  {
    $tierList->update($validatedData);
    $tierList->save();

    $this->flushUserTierListInfoCache($tierList->user_id);
    $this->flushTierListCacheByID($tierList->id);

    $isUpdatingIsPublicField = array_key_exists('is_public', $validatedData);
    if ($tierList->is_public || $isUpdatingIsPublicField) {
      $this->flushAllPublicCache();
    }

    return $tierList;
  }

  public function recent(): Collection
  {
    return Cache::tags([static::ALL_CACHE, static::PUBLIC_CACHE])->rememberForever(
      key: static::RECENT_CACHE,
      callback: fn () => TierList::select('id', 'title', 'description', 'thumbnail', Model::UPDATED_AT, User::FOREIGN_KEY)
        ->whereIsPublic()
        ->orderByRecency()
        ->with('creator:id,username')
        ->take(4)
        ->get()
    );
  }

  public function destroy(TierList $tierList)
  {
    TierList::destroy($tierList->id);

    $this->flushUserTierListInfoCache($tierList->user_id);
    $this->flushTierListCacheByID($tierList->id);

    if ($tierList->is_public) {
      $this->flushAllPublicCache();
    }

  }

  public function destroyAll(array $tierListIDs, bool $flushCache = true)
  {
    TierList::destroy($tierListIDs);

    if ($flushCache) {
      Cache::flush();
    }
  }

  public function getBatch(Authenticatable $user, int $batchSize = 5): Collection
  {
    return TierList::where(User::FOREIGN_KEY, $user->id)->take($batchSize)->get();
  }

  public function flushUserTierListInfoCache(string $userID)
  {
    Cache::tags([$userID])->flush();
  }

  public function flushTierListCacheByID(string $tierListID)
  {
    Cache::tags([static::ALL_CACHE])->forget($tierListID);
  }

  public function flushAllPublicCache()
  {
    Cache::tags([static::PUBLIC_CACHE])->flush();
  }
}
