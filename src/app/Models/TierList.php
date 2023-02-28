<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TierList extends Model
{
    use HasFactory, HasUuids;

    const FOREIGN_KEY = 'tier_list_id';

    protected $fillable = [
        'title',
        'data',
        'user_id',
        'description'
    ];

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
}
