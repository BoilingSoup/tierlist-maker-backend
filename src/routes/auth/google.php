<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/google/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->id],
            [
                'username' => $googleUser->nickname ?? $googleUser->name, // TODO: make uuid if both are null
                'email' => $googleUser->email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]
        );

        Auth::login($user);

        return redirect(config('app.frontend_url'));
    } catch (\Exception) {
        return redirect(config('app.frontend_url'));
    }
});
