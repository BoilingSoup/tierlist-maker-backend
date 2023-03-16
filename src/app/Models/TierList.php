<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TierList extends Model
{
    use HasFactory, HasUuids;

    const TABLE = 'tier_lists';

    const FOREIGN_KEY = 'tier_list_id';

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, User::FOREIGN_KEY);
    }

    public function liked_by(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'reactions', TierList::FOREIGN_KEY, User::FOREIGN_KEY)
          ->wherePivot('like', true)
          ->withTimestamps();
    }

    public function disliked_by(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'reactions', TierList::FOREIGN_KEY, User::FOREIGN_KEY)
          ->wherePivot('dislike', true)
          ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, Categories::FOREIGN_KEY);
    }

    public function scopeOrderByRecency(Builder $builder): Builder
    {
        return $builder->orderBy(Model::CREATED_AT, 'desc');
    }

    public function scopeWhereIsPublic(Builder $builder): Builder
    {
        return $builder->where('is_public', true);
    }
}
