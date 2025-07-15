<?php

use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/products', function () {
    return view('products');
})->name('products');

Route::get('/product/{id}', function ($id) {
    return view('product-details', compact('id'));
})->name('product.details');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');


Route::get('/policy', [StaticController::class, 'policy'])->name('policy');







Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
