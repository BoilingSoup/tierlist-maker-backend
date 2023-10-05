<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\TierList;

class DataHandlerService
{
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
}
