<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Database\Helpers\MaxLength;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): Response
  {
    $request->validate([
      'username' => ['required', 'string', "max:" . MaxLength::USERS_USERNAME],
      'email' => ['required', 'string', 'email', 'max:' . MaxLength::USERS_EMAIL, 'unique:' . User::class],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
      'username' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'is_admin' => false
    ]);

    event(new Registered($user));

    Auth::login($user);

    return response()->noContent();
  }
}
