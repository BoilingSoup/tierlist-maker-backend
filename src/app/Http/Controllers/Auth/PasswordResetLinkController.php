<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
  /**
   * Handle an incoming password reset link request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): JsonResponse
  {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    // We will send the password reset link to this user. Once we have attempted
    // to send the link, we will examine the response then see the message we
    // need to show to the user. Finally, we'll send out a proper response.

    /** @var User */
    $user = User::where('email', $request->email)->wherePasswordIsNotNull()->first();
    if (is_null($user)) {
      abort(400, 'Invalid request');
    }

    $token = Password::getRepository()->create($user);
    $user->sendPasswordResetNotification($token);

    return response()->json(['status' => 'We have emailed your password reset link.']);
  }
}
