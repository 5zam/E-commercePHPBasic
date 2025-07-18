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

// Shop Routes
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/shop/category/{category}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Cart Routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    // AJAX Routes
    Route::get('/count', [CartController::class, 'count'])->name('cart.count');
    Route::get('/items', [CartController::class, 'items'])->name('cart.items');
});

// Debug Routes (للاختبار فقط)
Route::get('/debug-cart', function () {
    $debug = [];
    
    try {
        $debug['session_id'] = session()->getId();
        $debug['cart_service_exists'] = class_exists(\App\Services\CartService::class);
        
        if ($debug['cart_service_exists']) {
            $cartService = app(\App\Services\CartService::class);
            $debug['cart_contents'] = $cartService->get();
            $debug['cart_count'] = $cartService->count();
            $debug['cart_key'] = $cartService->getCartKey();
        }
        
        $debug['products_count'] = \App\Models\Product::count();
        
    } catch (\Exception $e) {
        $debug['error'] = $e->getMessage();
    }
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
});

// أضف هذا Route في web.php للتحقق من المنتجات
Route::get('/check-products', function () {
    $products = \App\Models\Product::all();
    
    $result = [
        'total_products' => $products->count(),
        'product_ids' => $products->pluck('id')->toArray(),
        'products_details' => $products->map(function($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->price,
                'stock' => $product->stock
            ];
        })->toArray()
    ];
    
    return response()->json($result, 200, [], JSON_PRETTY_PRINT);
});

// أضف هذا Route في web.php لإضافة منتج حقيقي
Route::get('/add-real-product', function () {
    try {
        // احصل على أول منتج من قاعدة البيانات
        $product = \App\Models\Product::first();
        
        if (!$product) {
            return ['error' => 'No products found in database'];
        }
        
        // أضف للسلة
        $cartService = app(\App\Services\CartService::class);
        
        // امسح السلة أولاً
        $cartService->clear();
        
        // أضف المنتج الحقيقي
        $cartService->add(
            $product->id,
            1,
            $product->price,
            [
                'title' => $product->title,
                'image' => $product->image,
                'slug' => $product->slug
            ]
        );
        
        return [
            'success' => true,
            'product_added' => [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->price
            ],
            'cart_count' => $cartService->count(),
            'cart_contents' => $cartService->get()
        ];
        
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ];
    }
});

// أضف هذا Route في web.php للتشخيص
Route::get('/debug-cart-controller', function () {
    try {
        $cartService = app(\App\Services\CartService::class);
        $cartItems = $cartService->get();
        
        $debug = [
            'step1_cart_items' => $cartItems,
            'step2_is_empty' => empty($cartItems),
            'step3_product_ids' => array_keys($cartItems),
        ];
        
        if (!empty($cartItems)) {
            $productIds = array_keys($cartItems);
            $debug['step4_query_products'] = "Product::whereIn('id', [" . implode(',', $productIds) . "])->get()";
            
            $products = \App\Models\Product::whereIn('id', $productIds)->get();
            $debug['step5_products_found'] = $products->count();
            $debug['step6_products_data'] = $products->map(function($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'image' => $p->image,
                    'image_url' => $p->image_url,
                    'price' => $p->price
                ];
            })->toArray();
            
            // محاكاة ما يحدث في CartController
            $cart = [];
            foreach ($cartItems as $productId => $item) {
                $product = $products->find($productId);
                
                if ($product) {
                    $cart[] = [
                        'product' => [
                            'id' => $product->id,
                            'title' => $product->title,
                            'image_url' => $product->image_url,
                            'price' => $product->price,
                            'stock' => $product->stock
                        ],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? $product->price,
                        'subtotal' => ($item['price'] ?? $product->price) * $item['quantity']
                    ];
                } else {
                    $debug['missing_product'] = $productId;
                }
            }
            
            $debug['step7_final_cart'] = $cart;
            $debug['step8_cart_count'] = count($cart);
            $debug['step9_total'] = $cartService->subtotal();
        }
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500, [], JSON_PRETTY_PRINT);
    }
});


// أضف هذا Route المؤقت في web.php
Route::get('/quick-add', function () {
    try {
        $cartService = app(\App\Services\CartService::class);
        $product = \App\Models\Product::find(1); // TekWatch Pro Max
        
        if (!$product) {
            return ['error' => 'Product not found'];
        }
        
        // أضف المنتج للسلة
        $cartService->add(
            $product->id,
            1,
            $product->price,
            [
                'title' => $product->title,
                'image' => $product->image,
                'slug' => $product->slug
            ]
        );
        
        return [
            'success' => true,
            'message' => 'Product added to cart for logged in user',
            'user_id' => auth()->id(),
            'cart_key' => $cartService->getCartKey(),
            'cart_count' => $cartService->count(),
            'redirect_to' => '/cart'
        ];
        
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
});

// أضف هذا Route في web.php
Route::get('/fix-cart', function () {
    try {
        $userId = auth()->id();
        
        if (!$userId) {
            return ['error' => 'User not logged in'];
        }
        
        // المفاتيح المختلفة
        $userKey = 'cart:user_' . $userId;
        $sessionKey = 'cart:session_' . session()->getId();
        
        // تحقق من وجود البيانات في مفاتيح مختلفة
        $userCart = \Illuminate\Support\Facades\Redis::get($userKey);
        $sessionCart = \Illuminate\Support\Facades\Redis::get($sessionKey);
        
        // ابحث عن جميع مفاتيح السلة للمستخدم
        $allKeys = \Illuminate\Support\Facades\Redis::keys('cart:*');
        $foundCarts = [];
        
        foreach ($allKeys as $key) {
            $cartData = \Illuminate\Support\Facades\Redis::get($key);
            if ($cartData) {
                $foundCarts[$key] = json_decode($cartData, true);
            }
        }
        
        // إذا وُجدت سلة في cart:user_4، انسخها للمفتاح الصحيح
        $correctKey = 'cart:user_' . $userId;
        if (isset($foundCarts[$correctKey]) && !empty($foundCarts[$correctKey])) {
            return [
                'status' => 'Cart already exists in correct location',
                'cart_key' => $correctKey,
                'cart_contents' => $foundCarts[$correctKey],
                'cart_count' => count($foundCarts[$correctKey])
            ];
        }
        
        // ابحث عن أي سلة تحتوي على منتجات وانقلها
        foreach ($foundCarts as $key => $cart) {
            if (!empty($cart) && strpos($key, 'user_' . $userId) !== false) {
                // انسخ السلة للمفتاح الصحيح
                \Illuminate\Support\Facades\Redis::setex($correctKey, 86400, json_encode($cart));
                
                return [
                    'status' => 'Cart moved successfully',
                    'from' => $key,
                    'to' => $correctKey,
                    'cart_contents' => $cart,
                    'cart_count' => count($cart)
                ];
            }
        }
        
        // إذا لم توجد سلة، أنشئ واحدة جديدة
        $cartService = app(\App\Services\CartService::class);
        $product = \App\Models\Product::find(1);
        
        if ($product) {
            $cartService->add(
                $product->id,
                1,
                $product->price,
                [
                    'title' => $product->title,
                    'image' => $product->image,
                    'slug' => $product->slug
                ]
            );
            
            return [
                'status' => 'New cart created',
                'cart_key' => $cartService->getCartKey(),
                'cart_contents' => $cartService->get(),
                'cart_count' => $cartService->count()
            ];
        }
        
        return [
            'status' => 'No action taken',
            'found_carts' => $foundCarts,
            'user_id' => $userId,
            'expected_key' => $correctKey
        ];
        
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ];
    }
});


//test image
Route::get('/test-images', function() {
    $products = \App\Models\Product::all();
    foreach($products as $product) {
        echo "Product: " . $product->title . "<br>";
        echo "Image field: " . $product->image . "<br>";
        echo "Image URL: " . $product->image_url . "<br>";
        echo "File exists: " . (file_exists(public_path('storage/' . $product->image)) ? 'Yes' : 'No') . "<br><br>";
    }
});

Route::get('/debug-storage', function() {
    echo "Storage path: " . storage_path('app/public/products') . "<br>";
    echo "Public storage path: " . public_path('storage/products') . "<br>";
    echo "Storage link exists: " . (is_link(public_path('storage')) ? 'Yes' : 'No') . "<br>";
    echo "Products folder exists: " . (is_dir(storage_path('app/public/products')) ? 'Yes' : 'No') . "<br>";
});