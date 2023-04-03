<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/imgur/redirect', function () {
    return Socialite::driver('imgur')->redirect();
});

Route::get('/imgur/callback', function () {
    try {
        $imgurUser = Socialite::driver('imgur')->user();

        $user = User::updateOrCreate(
            ['imgur_id' => $imgurUser->id],
            [
                'username' => $imgurUser->nickname ?? $imgurUser->name, // TODO: make uuid if both are null
                'email' => $imgurUser->email,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'imgur_token' => $imgurUser->token,
                'imgur_refresh_token' => $imgurUser->refreshToken,
            ]
        );

        Auth::login($user);

        return redirect('/');
    } catch (\Exception) {
        // return redirect(config('view.frontendUrl'));
        return redirect('/');
    }
});
