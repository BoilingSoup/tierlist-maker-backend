<?php

use App\Http\Controllers\TierListController;
use Illuminate\Support\Facades\Route;

Route::name('v1.tierlist.')->group(function () {

  Route::get('/tierlist/recent', [TierListController::class, 'recent'])->name('recent');

  Route::get('/tierlist/{uuid}', [TierListController::class, 'show'])->whereUuid('uuid')->name('show');

  Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::post('/tierlist', [TierListController::class, 'store'])->name('store');

    Route::put('/tierlist/{uuid}', [TierListController::class, 'update'])->whereUuid('uuid')->name('update');

    Route::patch('/tierlist/{uuid}', [TierListController::class, 'updateInfo'])->whereUuid('uuid')->name('updateInfo');

    Route::patch('/tierlist/{uuid}/isPublic', [TierListController::class, 'updatePublicStatus'])->whereUuid('uuid')->name('updatePublicStatus');

    Route::get('/user/{userID}/tierlists', [TierListController::class, 'indexOfUser'])->whereUuid('userID')->name('indexUser');

    Route::delete('/tierlist/{uuid}', [TierListController::class, 'destroy'])->whereUuid('uuid')->name('destroy');
  });
});
