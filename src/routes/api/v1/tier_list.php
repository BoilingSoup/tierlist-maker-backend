<?php

use App\Http\Controllers\TierListController;
use Illuminate\Support\Facades\Route;

Route::name('v1.tierlist.')->group(function () {

  Route::get('/tierlist/recent', [TierListController::class, 'recent'])->name('recent');

  Route::get('/tierlist/{uuid}', [TierListController::class, 'show'])->whereUuid('uuid')->name('show');

  Route::post('/tierlist', [TierListController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('store');

  Route::put('/tierlist/{uuid}', [TierListController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->whereUuid('uuid')->name('update');

  Route::get('/user/{userID}/tierlists', [TierListController::class, 'indexOfUser'])->middleware(['auth:sanctum', 'verified'])->whereUuid('userID')->name('indexUser');
});
