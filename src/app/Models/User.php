<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const FOREIGN_KEY = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'is_admin'
    ];

    public function tier_lists(): HasMany
    {
        return $this->hasMany(TierList::class);
    }

    public function reactions(): BelongsToMany
    {
        return $this->belongsToMany(TierList::class, 'reactions', User::FOREIGN_KEY, TierList::FOREIGN_KEY)
                    ->withPivot(['like', 'dislike'])
                    ->withTimestamps();
    }
}
