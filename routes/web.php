<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
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

Route::redirect('/', '/users');

Route::name('users.')->group(function () {
  Route::get('/users', [UserController::class, 'index'])->name('index');
  Route::post('/users', [UserController::class, 'store'])->name('store');
});

Route::name('tags.')->group(function () {
  Route::get('/tags', [TagController::class, 'index'])->name('index');
  Route::post('/tags', [TagController::class, 'store'])->name('store');
  Route::put('/tags/{tag}', [TagController::class, 'update'])->name('update');
});
