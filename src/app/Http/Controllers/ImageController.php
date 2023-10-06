<?php

namespace App\Http\Controllers;

use App\Helpers\AuthorizationHelper;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Requests\ReplaceThumbnailRequest;
use App\Repositories\TierListRepository;
use App\Services\ImageManagementService;

class ImageController extends Controller
{
  public TierListRepository $tierListRepository;

  public ImageManagementService $imageManagementService;

  public function __construct(TierListRepository $tierListRepository, ImageManagementService $imageManagementService)
  {
    $this->tierListRepository = $tierListRepository;
    $this->imageManagementService = $imageManagementService;
  }

  public function store(ImageUploadRequest $request)
  {
    $validatedImages = $request->validated()['image'];

    $paths = $this->imageManagementService->uploadImages($validatedImages);

    return ['data' => $paths];
  }

  public function replaceThumbnail(ReplaceThumbnailRequest $request, string $uuid)
  {
    $newThumbnail = $request->validated()['thumbnail'];

    $tierList = $this->tierListRepository->getOrFail($uuid);

    if (! AuthorizationHelper::canUpdateTierList($tierList)) {
      abort(404);

      return;
    }

    $path = $this->imageManagementService->replaceThumbnail($tierList, $newThumbnail);

    $this->tierListRepository->update($tierList, [
        'thumbnail' => $path,
    ]);

    return response()->noContent();
  }
}
