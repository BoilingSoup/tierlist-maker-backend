<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public UserRepository $repository;

  public function __construct(UserRepository $repository)
  {
    $this->repository = $repository;
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    return new UserResource($request->user());
  }

  /**
   * Update the Authenticated User's username or email.
   */
  public function update(UpdateUserRequest $request)
  {
    $validated = $request->validated();

    if (count($validated) !== 1) {
      abort(422);
    }

    $isOauth = (bool) Auth::user()->getOAuthProvider();

    if (array_key_first($validated) === 'email' && $isOauth) {
      abort(403);
    }

    $user = $this->repository->update($validated);

    return new UserResource($user);
  }

  public function changePassword(ChangePasswordRequest $request)
  {
    $validated = $request->validated(); // validation will throw an error if current_password is incorrect.

    $isOauthAccount = (bool) Auth::user()->getOAuthProvider();
    abort_if($isOauthAccount, 403);

    $this->repository->changePassword($validated);

    return response()->noContent();
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
