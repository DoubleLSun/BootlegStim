<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GamePageController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

// Game / product page
Route::get('/games/{game}', [GamePageController::class, 'show'])->name('games.show');

// Profile pages
Route::get('/profile/{user}',         [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/{user}/edit',    [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/{user}/update', [ProfileController::class, 'update'])->name('profile.update');

// ── Auth ─────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
