<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\TierList;
use App\Repositories\TierListRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class DataHandlerService
{
  public TierListRepository $tierListRepository;

  public ImageManagementService $imageManagementService;

  public function __construct(TierListRepository $tierListRepository, ImageManagementService $imageManagementService)
  {
    $this->tierListRepository = $tierListRepository;
    $this->imageManagementService = $imageManagementService;
  }

  /**
   * Compares img src URLs of old vs new data, and returns an array of image IDs no longer in use.
   */
  public function getDeletedImageIDs(TierList $tierList, array $validatedData): array
  {
    $allValidatedDataImages = [];

    $validatedSidebar = $validatedData['data']['sidebar'];
    $validatedRows = $validatedData['data']['rows'];

    foreach ($validatedSidebar as $image) {
      $src = $image['src'];
      $allValidatedDataImages[$src] = true;
    }

    foreach ($validatedRows as $row) {
      $images = $row['items'];

      foreach ($images as $image) {
        $src = $image['src'];
        $allValidatedDataImages[$src] = true;
      }
    }

    $currTierListData = json_decode($tierList->data, true);
    $currRows = $currTierListData['rows'];
    $currSidebar = $currTierListData['sidebar'];

    $deletedImageIDs = [];

    foreach ($currRows as $row) {
      $images = $row['items'];

      foreach ($images as $image) {
        $src = $image['src'];

        if (array_key_exists($src, $allValidatedDataImages)) {
          continue;
        }

        array_push($deletedImageIDs, ImageHelper::UrlToPublicID($src));
      }
    }

    foreach ($currSidebar as $image) {
        $src = $image['src'];

        if (array_key_exists($src, $allValidatedDataImages)) {
          continue;
        }

        array_push($deletedImageIDs, ImageHelper::UrlToPublicID($src));
    }

    return $deletedImageIDs;
  }

  public function getAllImageIDs(TierList $tierList, bool $includeThumbnailID = false): array
  {
    $allIDs = [];

    $tierListData = json_decode($tierList->data, true);
    $rows = $tierListData['rows'];
    $sidebar = $tierListData['sidebar'];

    foreach ($sidebar as $image) {
      $src = $image['src'];
      array_push($allIDs, ImageHelper::UrlToPublicID($src));
    }

    foreach ($rows as $row) {
      $images = $row['items'];

      foreach ($images as $image) {
        $src = $image['src'];
        array_push($allIDs, ImageHelper::UrlToPublicID($src));
      }
    }

    if ($includeThumbnailID) {
      array_push($allIDs, ImageHelper::UrlToPublicID($tierList->thumbnail));
    }

    return $allIDs;
  }

  public function deleteAllTierListsInBatches(Authenticatable $user, int $batchSize = 5)
  {
    $batch = $this->tierListRepository->getBatch(user: $user, batchSize: $batchSize);

    function deleteRecursively(
      Collection $collection,
      TierListRepository $tierListRepository,
      DataHandlerService $dataHandler,
      ImageManagementService $imageManager,
      int $batchSize, Authenticatable $user
    ) {
      if ($collection->count() === 0) {
        return;
      }

      $allIDs = [];

      $collection->each(function (TierList $tierList) use ($dataHandler, $imageManager, &$allIDs) {
        $allTierListImagesIDs = $dataHandler->getAllImageIDs($tierList, true);
        $imageManager->deleteImages($allTierListImagesIDs);

        unset($allTierListImagesIDs); // try to free some memory

        array_push($allIDs, $tierList->id);
      });
      unset($collection); // try to free some memory

      $tierListRepository->destroyAll($allIDs, flushCache: false);

      unset($allIDs); // try to free some memory

      $batch = $tierListRepository->getBatch(user: $user, batchSize: $batchSize);

      deleteRecursively(
        collection: $batch,
        tierListRepository: $tierListRepository,
        dataHandler: $dataHandler,
        imageManager: $imageManager,
        batchSize: $batchSize,
        user: $user
      );
    }

    deleteRecursively(
      collection: $batch,
      tierListRepository: $this->tierListRepository,
      dataHandler: $this,
      imageManager: $this->imageManagementService,
      batchSize: $batchSize,
      user: $user
    );
  }
}
