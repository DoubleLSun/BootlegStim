<?php

use App\Http\Controllers\CartPageController;
use App\Http\Controllers\GamePageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;

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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestDataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// game page route
Route::get('/games/{game}', [GamePageController::class, 'show'])->name('games.show');

// cart page routes (DB-backed via user_carts)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartPageController::class, 'show'])->name('cart.show');
    Route::get('/cart/data', [CartPageController::class, 'data'])->name('cart.data');
    Route::post('/cart/calculate-totals', [CartPageController::class, 'calculateTotals']);
    Route::post('/cart/remove-item', [CartPageController::class, 'removeItem']);
    Route::post('/cart/clear', [CartPageController::class, 'clearCart']);
    Route::post('/cart/update-quantity', [CartPageController::class, 'updateQuantity']);
});

// test data routes (for development/testing React setup)
Route::prefix('test')->name('test.')->middleware('auth')->group(function () {
    Route::get('/populate-cart', [TestDataController::class, 'populateCart'])->name('populate-cart');
    Route::get('/clear-cart', [TestDataController::class, 'clearTestCart'])->name('clear-cart');
    Route::get('/cart-status', [TestDataController::class, 'cartStatus'])->name('cart-status');
});

// authentication routes
// Note: Using custom AuthController instead of Laravel's built-in Auth::routes() for more control over authentication flow and views
// Auth::routes();

// home page route after login
Route::get('/home', [HomeController::class, 'index'])->name('home');



//Route::middleware(['auth'])->group(function () {
 
    // ---- Game Library ----
    Route::get('/library', [LibraryController::class, 'libraryPage'])->name('library.libraryPage');
    Route::get('/library/{id}', [LibraryController::class, 'show'])->name('library.show');
 
    // ---- Payment / Checkout ----
    Route::get('/checkout', [PaymentController::class, 'paymentPage'])->name('payment.paymentPage');
    Route::post('/checkout/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/checkout/promo', [PaymentController::class, 'applyPromo'])->name('payment.promo');
    Route::post('/checkout/wallet', [PaymentController::class, 'toggleWallet'])->name('payment.wallet.toggle');
//});

// Profile pages
// Profile route needs to check if there is a logged in user before directing to the profile page, 
// if not logged in, redirect to login page
Route::middleware('auth')->group(function () {
    Route::get('/profile', function(){
        return redirect()->route('profile.show', auth()->user());
    })->name('profile');
    Route::get('/profile/{user}',         [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{user}/edit',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/{user}/update', [ProfileController::class, 'update'])->name('profile.update');
});

// ── Auth ─────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
