<?php

namespace App\Http\Controllers;

use App\Helpers\AuthorizationHelper;
use App\Http\Requests\SaveNewTierListRequest;
use App\Http\Requests\UpdatePublicStatusRequest;
use App\Http\Requests\UpdateTierListInfoRequest;
use App\Http\Requests\UpdateTierListRequest;
use App\Repositories\TierListRepository;
use App\Services\DataHandlerService;
use App\Services\ImageManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TierListController extends Controller
{
    protected TierListRepository $repository;

    protected ImageManagementService $imageManagementService;

    protected DataHandlerService $dataHandlerService;

    public function __construct(TierListRepository $repository, ImageManagementService $imageManagementService, DataHandlerService $dataHandlerService)
    {
        $this->repository = $repository;
        $this->imageManagementService = $imageManagementService;
        $this->dataHandlerService = $dataHandlerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    //
    }

    /**
     * Display a listing of the resource owned by the authenticated user.
     */
    public function indexOfUser(Request $request)
    {
      $userID = Auth::user()?->id;
      $cursor = $request->cursor ?? '';

      return $this->repository->getUserTierListsInfo($userID, $cursor);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveNewTierListRequest $request)
    {
      $validated = (array) $request->validated();

      $savedData = $this->repository->store($validated);

      return $savedData;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
      $tierList = $this->repository->getOrFail($uuid);

      if (! AuthorizationHelper::canShowTierList($tierList)) {
        abort(404);

        return;
      }

      return $tierList;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTierListRequest $request, string $uuid)
    {
      $validated = (array) $request->validated();

      $tierList = $this->repository->getOrFail($uuid);

      if (! AuthorizationHelper::canUpdateTierList($tierList)) {
        abort(403);

        return;
      }

      $unusedImageIDs = $this->dataHandlerService->getDeletedImageIDs($tierList, $validated);

      $this->imageManagementService->deleteImages($unusedImageIDs);

      $updated = $this->repository->update($tierList, $validated);

      return $updated;
    }

    public function updateInfo(UpdateTierListInfoRequest $request, string $uuid)
    {
      $validated = (array) $request->validated();

      $tierList = $this->repository->getOrFail($uuid);

      if (! AuthorizationHelper::canUpdateTierList($tierList)) {
        abort(403);

        return;
      }

      $updated = $this->repository->update($tierList, $validated);

      return $updated;
    }

    public function updatePublicStatus(UpdatePublicStatusRequest $request, string $uuid)
    {
      $validated = (array) $request->validated();

      $tierList = $this->repository->getOrFail($uuid);

      if (! AuthorizationHelper::canUpdateTierList($tierList)) {
        abort(403);

        return;
      }

      $updated = $this->repository->update($tierList, $validated);

      return $updated;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
      $tierList = $this->repository->getOrFail($uuid);

      if (! AuthorizationHelper::canDeleteTierList($tierList)) {
        abort(403);

        return;
      }

      $allImageIDs = $this->dataHandlerService->getAllImageIDs($tierList, includeThumbnailID: true);

      $this->imageManagementService->deleteImages($allImageIDs);

      $this->repository->destroy($tierList);

      return response()->noContent();
    }

    /**
     * Get the 4 most recent public tier lists to display on home page carousel.
     */
    public function recent()
    {
        return $this->repository->recent();
    }
}
