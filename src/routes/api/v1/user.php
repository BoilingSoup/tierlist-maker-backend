<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/user', [UserController::class, 'show'])->middleware(['auth:sanctum'])->name('v1.user.show');
Route::patch('/user', [UserController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('v1.user.update');
Route::patch('/user/change-password', [UserController::class, 'changePassword'])->middleware(['auth:sanctum', 'verified'])->name('v1.user.changePassword');
