<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
  /**
   * Handle an incoming new password request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): JsonResponse
  {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    /** @var User */
    $user = User::where('email', $request->email)->wherePasswordIsNotNull()->first();
    if (is_null($user)) {
      abort(400, 'Invalid request');
    }

    $tokenIsValid = Password::getRepository()->exists($user, $request->token);
    if (! $tokenIsValid) {
      abort(400, 'Invalid request');
    }

    $user->forceFill([
        'password' => Hash::make($request->password),
        'remember_token' => Str::random(60),
    ])->save();

    event(new PasswordReset($user));

    Password::getRepository()->delete($user);

    return response()->json(['status' => 'Your password was reset successfully.']);
  }
}
