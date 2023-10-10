<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

Route::get('/github/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/github/callback', function () {
    try {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate(
            ['github_id' => $githubUser->id],
            [
                'username' => $githubUser->nickname ?? $githubUser->name ?? Uuid::uuid4(),
                'email' => $githubUser->email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]
        );

        Auth::login($user);

        return redirect(config('app.frontend_url'));
    } catch (\Exception) {
        return redirect(config('app.frontend_url'));
    }
});
