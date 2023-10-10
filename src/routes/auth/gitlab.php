<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

Route::get('/gitlab/redirect', function () {
    return Socialite::driver('gitlab')->redirect();
});

Route::get('/gitlab/callback', function () {
    try {
        $gitlabUser = Socialite::driver('gitlab')->user();

        $user = User::updateOrCreate(
            ['gitlab_id' => $gitlabUser->id],
            [
                'username' => $gitlabUser->nickname ?? $gitlabUser->name ?? Uuid::uuid4(),
                'email' => $gitlabUser->email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'gitlab_token' => $gitlabUser->token,
                'gitlab_refresh_token' => $gitlabUser->refreshToken,
            ]
        );

        Auth::login($user);

        return redirect(config('app.frontend_url'));
    } catch (\Exception) {
        return redirect(config('app.frontend_url'));
    }
});
