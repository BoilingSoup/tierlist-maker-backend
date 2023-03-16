<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/gitlab/redirect', function () {
    return Socialite::driver('gitlab')->redirect();
});

Route::get('/gitlab/callback', function () {
    try {
        $gitlabUser = Socialite::driver('gitlab')->user();

        $user = User::updateOrCreate(
            ['gitlab_id' => $gitlabUser->id],
            [
                'name' => $gitlabUser->name,
                'avatar' => $gitlabUser->avatar,
                'email' => $gitlabUser->email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'gitlab_token' => $gitlabUser->token,
                'gitlab_refresh_token' => $gitlabUser->refreshToken,
            ]
        );

        Auth::login($user);
        dump(Auth::check());

        return redirect(config('view.frontendUrl'));
    } catch (\Exception) {
        return redirect(config('view.frontendUrl'));
    }
});
