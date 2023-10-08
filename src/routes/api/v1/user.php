<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::name('v1.user.')->group(function () {
  Route::get('/user', [UserController::class, 'show'])->middleware(['auth:sanctum'])->name('show');

  Route::patch('/user', [UserController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('update');

  Route::patch('/user/change-password', [UserController::class, 'changePassword'])->middleware(['auth:sanctum', 'verified'])->name('changePassword');

  Route::delete('/user', [UserController::class, 'destroy'])->middleware(['auth:sanctum'])->name('destroy');
});
