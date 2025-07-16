<?php

use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.alt');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Password reset routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Static pages
Route::get('/policy', [StaticController::class, 'policy'])->name('policy');

// Voyager admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

// Route::get('/policy', [StaticController::class, 'policy'])->name('policy');

// Shop Routes
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/shop/category/{category}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// // API Routes for AJAX
// Route::get('/api/products/search', [ProductController::class, 'apiSearch'])->name('api.products.search');
// Route::get('/api/product/{id}/quick-view', [ProductController::class, 'quickView'])->name('api.product.quick-view');






// Route::group(['prefix' => 'admin'], function () {
//     Voyager::routes();
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
