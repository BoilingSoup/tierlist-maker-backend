<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::post('/image', [ImageController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('v1.image.store');
