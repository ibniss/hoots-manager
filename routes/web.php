<?php

use App\Http\Controllers\FormulaController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerTagController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/players');

Route::name('players.')->group(function () {
  Route::get('/players', [PlayerController::class, 'index'])->name('index');
  Route::put('/players/{player}/tags/{name}', [PlayerTagController::class, 'update'])->name('tags.update');
});

Route::name('tags.')->group(function () {
  Route::get('/tags', [TagController::class, 'index'])->name('index');
  Route::post('/tags', [TagController::class, 'store'])->name('store');
  Route::put('/tags/{tag}', [TagController::class, 'update'])->name('update');
  Route::delete('/tags/{tag}', [TagController::class, 'delete'])->name('delete');
});

Route::name('formulas.')->group(function () {
  Route::get('/formulas', [FormulaController::class, 'index'])->name('index');
  Route::post('/formulas', [FormulaController::class, 'store'])->name('store');
  Route::put('/formulas/{formula}/move', [FormulaController::class, 'move'])->name('move');
  Route::delete('/formulas/{formula}', [FormulaController::class, 'delete'])->name('delete');
});

Route::name('settings.')->group(function () {
  Route::get('/settings', [SettingController::class, 'index'])->name('index');
  Route::put('/settings', [SettingController::class, 'update'])->name('update');
});
