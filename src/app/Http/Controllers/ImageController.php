<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
  public function store(ImageUploadRequest $request)
  {
    $validatedImages = $request->validated()['image'];

    // Log::debug($validated['image'][0]->getRealPath());
    $paths = [];

    foreach ($validatedImages as $image) {
      array_push($paths, Cloudinary::upload($image->getRealPath())->getSecurePath());
    }

    return ['data' => $paths];
  }
}
