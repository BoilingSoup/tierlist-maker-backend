<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
  private bool $requestValidated = false;

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): UserResource|JsonResponse
  {
    if (User::emailAndPasswordExists($request->email)) {
      return response()->json(['message' => StatusHelper::UserWithEmailAlreadyExists], 403);
    }

    $this->validateCredentials($request);

    $user = $this->createUser($request);

    event(new Registered($user));

    Auth::login($user);

    return new UserResource($user);
  }

  private function validateCredentials(Request $request)
  {
    $request->validate([
        'username' => ['bail', 'required', 'string', 'max:20', 'min:4'],
        'email' => ['required', 'string', 'email', 'max:30'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $this->requestValidated = true;
  }

  private function createUser(Request $request)
  {
    if (! $this->requestValidated) {
      // Should never reach here. It's an extra safe-guard to
      // prevent logical errors in the future.
      return abort(500);
    }

    return User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
  }
}
