<?php

use App\Http\Controllers\MeleeApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.')->group(function () {
  Route::name('refresh.')->prefix('refresh')->group(function () {
    Route::post('players', [MeleeApiController::class, 'players'])->name('players');
    Route::post('standings', [MeleeApiController::class, 'standings'])->name('standings');
  });
});
