<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\TierList;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageManagementService
{
  public function deleteImages(array $imageIDs)
  {
    foreach ($imageIDs as $id) {
        Cloudinary::destroy($id);
    }
  }

  /**
   * Accepts an array of validated image files, uploads each image, and returns an array of paths to the uploaded images.
   */
  public function uploadImages(array $validatedImages): array
  {
    $paths = [];

    foreach ($validatedImages as $image) {
      array_push($paths, Cloudinary::upload($image->getRealPath())->getSecurePath());
    }

    return $paths;
  }

  /**
   * Accepts the current TierList model and the validated new thumbnail. Deletes the old thumbnail, uploads the new thumbnail, and returns the new thumbnail's URL.
   */
  public function replaceThumbnail(TierList $tierList, mixed $newThumbnail): string
  {
    $id = ImageHelper::UrlToPublicID($tierList->thumbnail);

    Cloudinary::destroy($id);

    return Cloudinary::upload($newThumbnail->getRealPath())->getSecurePath();
  }
}
