<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * These DB columns should be hidden by default.
     * If this data is necessary anywhere, use a resource class to pluck it out manually.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'github_token',
        'github_refresh_token',
        'gitlab_token',
        'gitlab_refresh_token',
        'google_token',
        'google_refresh_token',
        'reddit_token',
        'reddit_refresh_token',
        'discord_token',
        'discord_refresh_token',
        'imgur_token',
        'imgur_refresh_token',
        'remember_token',
    ];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //   'email_verified_at' => 'datetime',
    //   'is_admin' => 'boolean',
    // ];

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

    public function scopeWherePasswordIsNotNull(Builder $query)
    {
        return $query->whereNotNull('password');
    }

    /**
     * Retrieve user by email only where password is not null.
     *
     * @return Authenticatable | null
     */
    public static function findByEmailWherePasswordExists(string $email)
    {
        return static::where('email', $email)->wherePasswordIsNotNull()->first();
    }

    /**
     * Check if user exists and password is not null.
     *
     * @return bool
     */
    public static function emailAndPasswordExists(string $email)
    {
        return (bool) static::findByEmailWherePasswordExists($email);
    }

    /**
     * Return a query function to find a user by email, password must not be null.
     *
     * @return callable
     */
    public static function queryByEmailWherePasswordIsNotNull(string $email)
    {
        return function (Builder $query) use ($email) {
            $query->where('email', $email)->wherePasswordIsNotNull()->first();
        };
    }
}
