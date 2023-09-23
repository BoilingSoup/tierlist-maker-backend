<?php

use App\Http\Controllers\TierListController;
use Illuminate\Support\Facades\Route;

Route::get('/tierlist/recent', [TierListController::class, 'recent'])->name('v1.tierlist.recent');
Route::post('/tierlist', [TierListController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('v1.tierlist.store');
