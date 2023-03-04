<?php

namespace App\Repositories;

use App\Models\TierList;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class TierListRepository
{
    const ALL_CACHE = 'TLR';

    const RECENT_CACHE = 'TLR_R';

    public function recent()
    {
        return Cache::tags([static::ALL_CACHE])->rememberForever(
            key: static::RECENT_CACHE,
            // TODO: add a thumbnail column in tier_lists table, include that in select statement below.
            callback: fn () => TierList::where('is_public', true)->select('title', 'description', User::FOREIGN_KEY)->with('creator:id,username')->take(5)->get()
        );
    }
}
