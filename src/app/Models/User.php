<?php

namespace App\Models;

use App\Notifications\PasswordReset;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, CanResetPasswordContract
{
  use HasApiTokens, HasFactory, Notifiable, HasUuids, CanResetPassword;

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
      'remember_token',
  ];

  public function getOAuthProvider()
  {
    switch (true) {
      case ! is_null($this->github_id):
        return 'GITHUB';
      case ! is_null($this->gitlab_id):
        return 'GITLAB';
      case ! is_null($this->google_id):
        return 'GOOGLE';
      case ! is_null($this->reddit_id):
        return 'REDDIT';
      case ! is_null($this->discord_id):
        return 'DISCORD';
      default:
        return null;
    }
  }

  public function sendEmailVerificationNotification()
  {
    $this->notify(new VerifyEmail());
  }

  public function sendPasswordResetNotification($token)
  {
    $this->notify(new PasswordReset($token));
  }

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

  public function images(): BelongsToMany
  {
    $pivotTableName = Image::TABLE.'_'.User::TABLE;

    return $this->belongsToMany(Image::class, $pivotTableName, User::FOREIGN_KEY, Image::FOREIGN_KEY);
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
