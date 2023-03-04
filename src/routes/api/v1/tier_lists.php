<?php

use App\Http\Controllers\TierListController;
use Illuminate\Support\Facades\Route;

Route::get('/tierlist/recent', [TierListController::class, 'recent']);
