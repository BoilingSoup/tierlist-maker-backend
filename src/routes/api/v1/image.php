<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::name('v1.')->group(function () {
  Route::post('/image', [ImageController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('image.store');

  Route::post('/thumbnail/{uuid}', [ImageController::class, 'replaceThumbnail'])->whereUuid('uuid')
    ->middleware(['auth:sanctum', 'verified'])
    ->name('thumbnail.replace');
});
