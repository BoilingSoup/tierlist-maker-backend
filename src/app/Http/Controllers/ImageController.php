<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Http\Requests\ReplaceThumbnailRequest;
use App\Repositories\TierListRepository;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Nette\Utils\Arrays;
use Ramsey\Uuid\Uuid;

class ImageController extends Controller
{
  public TierListRepository $tierListRepository;

  public function __construct(TierListRepository $tierListRepository)
  {
    $this->tierListRepository = $tierListRepository;
  }

  public function store(ImageUploadRequest $request)
  {
    $validatedImages = $request->validated()['image'];

    $paths = [];

    foreach ($validatedImages as $image) {
      array_push($paths, Cloudinary::upload($image->getRealPath())->getSecurePath());
    }

    return ['data' => $paths];
  }

  public function replaceThumbnail(ReplaceThumbnailRequest $request, string $uuid)
  {
    $newThumbnail = $request->validated()['thumbnail'];

    if (! Uuid::isValid($uuid)) {
      abort(404);

      return;
    }

    $tierList = $this->tierListRepository->getOrFail($uuid);
    $isOwner = $tierList->user_id === Auth::user()?->id;

    if (! $isOwner) {
      abort(404);

      return;
    }

    $oldThumbnail = $tierList->thumbnail;
    $idWithExtension = Arrays::last(explode('/', $oldThumbnail));
    $id = Arrays::first(explode('.', $idWithExtension));

    Cloudinary::destroy($id);

    $this->tierListRepository->update($tierList, [
        'thumbnail' => Cloudinary::upload($newThumbnail->getRealPath())->getSecurePath(),
    ]);

    return response()->noContent();
  }
}
