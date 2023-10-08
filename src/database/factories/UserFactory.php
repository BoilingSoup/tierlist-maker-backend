<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
        'email' => fake()->unique()->safeEmail(),
        'username' => fake()->unique()->userName(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'is_admin' => false,
        'remember_token' => Str::random(10),
    ];
  }

  public function github()
  {
    return $this->state([
        'username' => 'dummyGithubUser',
        'email' => 'github@github.com',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'is_admin' => false,
        'remember_token' => Str::random(10),
        'github_id' => 1111,
    ]);
  }

  public function gitlab()
  {
    return $this->state([
        'username' => 'dummyGitlabUser',
        'email' => 'gitlab@gitlab.com',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'is_admin' => false,
        'remember_token' => Str::random(10),
        'gitlab_id' => 1111,
    ]);
  }

  public function google()
  {
    return $this->state([
        'username' => 'dummyGoogleUser',
        'email' => 'google@google.com',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'is_admin' => false,
        'remember_token' => Str::random(10),
        'google_id' => 1111,
    ]);
  }

  public function reddit()
  {
    return $this->state([
        'username' => 'dummyRedditUser',
        'email' => 'reddit@reddit.com',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'is_admin' => false,
        'remember_token' => Str::random(10),
        'reddit_id' => 1111,
    ]);
  }

  public function discord()
  {
    return $this->state([
        'username' => 'dummyDiscordUser',
        'email' => 'discord@discord.com',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'is_admin' => false,
        'remember_token' => Str::random(10),
        'discord_id' => 1111,
    ]);
  }

  public function bobby()
  {
    return $this->state([
        'username' => 'bobby',
        'email' => 'bobby@bobby.com',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'is_admin' => false,
        'remember_token' => Str::random(10),
    ]);
  }
}
