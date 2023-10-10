<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

Route::get('/discord/redirect', function () {
    return Socialite::driver('discord')->redirect();
});

Route::get('/discord/callback', function () {
    try {
        $discordUser = Socialite::driver('discord')->user();

        $user = User::updateOrCreate(
            ['discord_id' => $discordUser->id],
            [
                'username' => $discordUser->nickname ?? $discordUser->name ?? Uuid::uuid4(),
                'email' => $discordUser->email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'discord_token' => $discordUser->token,
                'discord_refresh_token' => $discordUser->refreshToken,
            ]
        );

        Auth::login($user);

        return redirect(config('app.frontend_url'));
    } catch (\Exception) {
        return redirect(config('app.frontend_url'));
    }
});
