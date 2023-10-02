<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::post('/image', [ImageController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('v1.image.store');
Route::post('/thumbnail/{uuid}', [ImageController::class, 'replaceThumbnail'])->middleware(['auth:sanctum', 'verified'])->name('v1.thumbnail.replace');
