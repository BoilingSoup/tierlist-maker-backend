<?php

namespace App\Http\Controllers;

use App\Helpers\StatusHelper;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\DataHandlerService;
use App\Services\ImageManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
  public UserRepository $repository;

  public DataHandlerService $dataHandlerService;

  public ImageManagementService $imageManagementService;

  public function __construct(UserRepository $repository, DataHandlerService $dataHandlerService, ImageManagementService $imageManagementService)
  {
    $this->repository = $repository;
    $this->dataHandlerService = $dataHandlerService;
    $this->imageManagementService = $imageManagementService;
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

    $isAttemptToUpdateMoreThanOneField = count($validated) !== 1;
    if ($isAttemptToUpdateMoreThanOneField) {
      abort(422);
    }

    $isAttemptToUpdateEmail = array_key_first($validated) === 'email';

    if (! $isAttemptToUpdateEmail) {
      // update username
      $user = $this->repository->update($validated);

      return new UserResource($user);
    }

    // update email ...

    $userRegisteredWithOauth = (bool) Auth::user()->getOAuthProvider();
    if ($userRegisteredWithOauth) {
      abort(403);
    }

    $newEmail = $validated['email'];
    if (Auth::user()->email !== $newEmail && User::emailAndPasswordExists($newEmail)) {
      return response()->json(['message' => StatusHelper::UserWithEmailAlreadyExists], 403);
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
  public function destroy()
  {
    $user = Auth::user();

    $this->dataHandlerService->deleteAllTierListsInBatches(user: $user, batchSize: 5);

    User::destroy($user->id);

    Cache::flush();

    response()->noContent();
  }
}
