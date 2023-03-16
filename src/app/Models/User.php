<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    const TABLE = 'users';

    const FOREIGN_KEY = 'user_id';

    public function tier_lists(): HasMany
    {
        return $this->hasMany(TierList::class);
    }

    public function liked_tierlists(): BelongsToMany
    {
        return $this->belongsToMany(TierList::class, 'reactions', User::FOREIGN_KEY, TierList::FOREIGN_KEY)
          ->wherePivot('like', true)
          ->withTimestamps();
    }

    public function disliked_tierlists(): BelongsToMany
    {
        return $this->belongsToMany(TierList::class, 'reactions', User::FOREIGN_KEY, TierList::FOREIGN_KEY)
          ->wherePivot('dislike', true)
          ->withTimestamps();
    }
}
