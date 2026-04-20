<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartPageController;
use App\Http\Controllers\GamePageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SearchController;


use App\Http\Controllers\StoreController;
use App\Http\Controllers\TestDataController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
    return redirect()->route('store.index');
});

// --- Main store page ---
Route::get('/store', [StoreController::class, 'index'])->name('store.index');

// Game / product page
Route::get('/games/{game}', [GamePageController::class, 'show'])->name('games.show');

// Search (public)
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/preview', [SearchController::class, 'preview'])->name('search.preview');

// Cart page routes (DB-backed via user_carts)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartPageController::class, 'show'])->name('cart.show');
    Route::get('/cart/data', [CartPageController::class, 'data'])->name('cart.data');
    Route::post('/cart/calculate-totals', [CartPageController::class, 'calculateTotals']);
    Route::post('/cart/remove-item', [CartPageController::class, 'removeItem']);
    Route::post('/cart/clear', [CartPageController::class, 'clearCart']);
    Route::post('/cart/update-quantity', [CartPageController::class, 'updateQuantity']);
});

// Test data routes (for development/testing React setup)
Route::prefix('test')->name('test.')->middleware('auth')->group(function () {
    Route::get('/populate-cart', [TestDataController::class, 'populateCart'])->name('populate-cart');
    Route::get('/clear-cart', [TestDataController::class, 'clearTestCart'])->name('clear-cart');
    Route::get('/cart-status', [TestDataController::class, 'cartStatus'])->name('cart-status');
});

// Home page route after login
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Game Library
    Route::get('/library', [LibraryController::class, 'libraryPage'])->name('library.libraryPage');
    Route::get('/library/{game}', [LibraryController::class, 'show'])->name('library.show');
    Route::post('/library/{game}/install', [LibraryController::class, 'install'])->name('library.install');
    Route::post('/library/{game}/play/start', [LibraryController::class, 'startPlay'])->name('library.play.start');
    Route::post('/library/{game}/play/stop', [LibraryController::class, 'stopPlay'])->name('library.play.stop');

    // Payment / Checkout
    Route::get('/checkout', [PaymentController::class, 'paymentPage'])->name('payment.paymentPage');
    Route::post('/checkout', [PaymentController::class, 'startCheckout'])->name('payment.start');
    Route::post('/checkout/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/checkout/promo', [PaymentController::class, 'applyPromo'])->name('payment.promo');
    Route::post('/checkout/wallet', [PaymentController::class, 'toggleWallet'])->name('payment.wallet.toggle');
});

// Profile pages (auth required)
Route::middleware('auth')->group(function () {
    Route::post('/games/{game}/cart', [GamePageController::class, 'addToCart'])->name('games.cart.add');
    Route::post('/games/{game}/comments', [GamePageController::class, 'storeComment'])->name('games.comments.store');
    Route::post('/games/{game}/comments/{review}/update', [GamePageController::class, 'updateComment'])->name('games.comments.update');
    Route::post('/games/{game}/comments/{review}/delete', [GamePageController::class, 'deleteComment'])->name('games.comments.delete');
    Route::post('/games/{game}/comments/{review}/helpful', [GamePageController::class, 'markHelpful'])->name('games.comments.helpful');

    Route::get('/profile', function () {
        // auth()->user() does store the user, and connects to the database
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

// Auth::routes();



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/manage-featured', [AdminController::class, 'manageFeatured'])->name('admin.manage');

    Route::post('/admin/games/{game}/toggle', [AdminController::class, 'toggleFeatured'])->name('admin.toggle');
    Route::post('/admin/games', [AdminController::class, 'createGame'])->name('admin.games.create');
    Route::post('/admin/games/{game}/update', [AdminController::class, 'updateGame'])->name('admin.games.update');
    Route::post('/admin/games/{game}/toggle-delist', [AdminController::class, 'toggleDelisted'])->name('admin.games.toggle-delist');

    Route::post('/admin/games/{game}/pricing-tag', [AdminController::class, 'setPricingTag'])->name('admin.games.pricing-tag');
    Route::post('/admin/games/{game}/pricings', [AdminController::class, 'createPricing'])->name('admin.pricings.create');
    Route::post('/admin/games/{game}/pricings/{pricing}/update', [AdminController::class, 'updatePricing'])->name('admin.pricings.update');
    Route::post('/admin/games/{game}/pricings/{pricing}/delete', [AdminController::class, 'deletePricing'])->name('admin.pricings.delete');

    Route::post('/admin/games/{game}/media', [AdminController::class, 'addMedia'])->name('admin.media.create');
    Route::post('/admin/games/{game}/media/{media}/delete', [AdminController::class, 'deleteMedia'])->name('admin.media.delete');

    Route::post('/admin/genres', [AdminController::class, 'createGenre'])->name('admin.genres.create');
    Route::post('/admin/genres/{genre}/update', [AdminController::class, 'updateGenre'])->name('admin.genres.update');
    Route::post('/admin/genres/{genre}/delete', [AdminController::class, 'deleteGenre'])->name('admin.genres.delete');
    Route::post('/admin/genres/{genre}/toggle-display', [AdminController::class, 'toggleGenreDisplay'])->name('admin.genres.toggle-display');
});

// Redirect invalid routes to home
Route::fallback(function () {
    // return response()->view('errors.404', [], 404);
    // Redirect invalid routes to home
    return redirect()->route('store.index');
});