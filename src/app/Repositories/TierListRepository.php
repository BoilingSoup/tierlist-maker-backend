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
            // NOTE: maybe include category in the query... if so, this query can be condensed into new scopes.
            callback: fn () => TierList::whereIsPublic()->select('title', 'description', 'thumbnail', User::FOREIGN_KEY)->with('creator:id,username')->take(5)->get()
        );
    }
}
