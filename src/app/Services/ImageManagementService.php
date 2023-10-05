<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageManagementService
{
  public function deleteImages(array $imageIDs)
  {
    foreach ($imageIDs as $id) {
        Cloudinary::destroy($id);
    }
  }
}
