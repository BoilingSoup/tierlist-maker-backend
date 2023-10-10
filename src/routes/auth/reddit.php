<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

Route::get('/reddit/redirect', function () {
    return Socialite::driver('reddit')->redirect();
});

Route::get('/reddit/callback', function () {
    try {
        $redditUser = Socialite::driver('reddit')->user();

        $user = User::updateOrCreate(
            ['reddit_id' => $redditUser->id],
            [
                'username' => $redditUser->nickname ?? $redditUser->name ?? Uuid::uuid4(),
                'email' => $redditUser->email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'reddit_token' => $redditUser->token,
                'reddit_refresh_token' => $redditUser->refreshToken,
            ]
        );

        Auth::login($user);

        return redirect(config('app.frontend_url'));
    } catch (\Exception) {
        return redirect(config('app.frontend_url'));
    }
});
