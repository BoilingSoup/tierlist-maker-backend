<?php

namespace App\Repositories;

use App\Models\TierList;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TierListRepository
{
    const ALL_CACHE = 'TLR';

    const RECENT_CACHE = 'TLR_R';

    public function recent()
    {
        return Cache::tags([static::ALL_CACHE])->rememberForever(
            key: static::RECENT_CACHE,
            callback: fn () => TierList::select('id', 'title', 'description', 'thumbnail', Model::CREATED_AT, User::FOREIGN_KEY)
              ->whereIsPublic()
              ->orderByRecency()
              ->with('creator:id,username')
              ->take(6)
              ->get()
        );
    }
}
