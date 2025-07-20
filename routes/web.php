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
use App\Http\Controllers\CheckoutController;

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





// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');




// // Debug Routes (للاختبار فقط)
// Route::get('/debug-cart', function () {
//     $debug = [];
    
//     try {
//         $debug['session_id'] = session()->getId();
//         $debug['cart_service_exists'] = class_exists(\App\Services\CartService::class);
        
//         if ($debug['cart_service_exists']) {
//             $cartService = app(\App\Services\CartService::class);
//             $debug['cart_contents'] = $cartService->get();
//             $debug['cart_count'] = $cartService->count();
//             $debug['cart_key'] = $cartService->getCartKey();
//         }
        
//         $debug['products_count'] = \App\Models\Product::count();
        
//     } catch (\Exception $e) {
//         $debug['error'] = $e->getMessage();
//     }
    
//     return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
// });

// // أضف هذا Route في web.php للتحقق من المنتجات
// Route::get('/check-products', function () {
//     $products = \App\Models\Product::all();
    
//     $result = [
//         'total_products' => $products->count(),
//         'product_ids' => $products->pluck('id')->toArray(),
//         'products_details' => $products->map(function($product) {
//             return [
//                 'id' => $product->id,
//                 'title' => $product->title,
//                 'price' => $product->price,
//                 'stock' => $product->stock
//             ];
//         })->toArray()
//     ];
    
//     return response()->json($result, 200, [], JSON_PRETTY_PRINT);
// });

// // أضف هذا Route في web.php لإضافة منتج حقيقي
// Route::get('/add-real-product', function () {
//     try {
//         // احصل على أول منتج من قاعدة البيانات
//         $product = \App\Models\Product::first();
        
//         if (!$product) {
//             return ['error' => 'No products found in database'];
//         }
        
//         // أضف للسلة
//         $cartService = app(\App\Services\CartService::class);
        
//         // امسح السلة أولاً
//         $cartService->clear();
        
//         // أضف المنتج الحقيقي
//         $cartService->add(
//             $product->id,
//             1,
//             $product->price,
//             [
//                 'title' => $product->title,
//                 'image' => $product->image,
//                 'slug' => $product->slug
//             ]
//         );
        
//         return [
//             'success' => true,
//             'product_added' => [
//                 'id' => $product->id,
//                 'title' => $product->title,
//                 'price' => $product->price
//             ],
//             'cart_count' => $cartService->count(),
//             'cart_contents' => $cartService->get()
//         ];
        
//     } catch (\Exception $e) {
//         return [
//             'error' => $e->getMessage(),
//             'line' => $e->getLine()
//         ];
//     }
// });

// // أضف هذا Route في web.php للتشخيص
// Route::get('/debug-cart-controller', function () {
//     try {
//         $cartService = app(\App\Services\CartService::class);
//         $cartItems = $cartService->get();
        
//         $debug = [
//             'step1_cart_items' => $cartItems,
//             'step2_is_empty' => empty($cartItems),
//             'step3_product_ids' => array_keys($cartItems),
//         ];
        
//         if (!empty($cartItems)) {
//             $productIds = array_keys($cartItems);
//             $debug['step4_query_products'] = "Product::whereIn('id', [" . implode(',', $productIds) . "])->get()";
            
//             $products = \App\Models\Product::whereIn('id', $productIds)->get();
//             $debug['step5_products_found'] = $products->count();
//             $debug['step6_products_data'] = $products->map(function($p) {
//                 return [
//                     'id' => $p->id,
//                     'title' => $p->title,
//                     'image' => $p->image,
//                     'image_url' => $p->image_url,
//                     'price' => $p->price
//                 ];
//             })->toArray();
            
//             // محاكاة ما يحدث في CartController
//             $cart = [];
//             foreach ($cartItems as $productId => $item) {
//                 $product = $products->find($productId);
                
//                 if ($product) {
//                     $cart[] = [
//                         'product' => [
//                             'id' => $product->id,
//                             'title' => $product->title,
//                             'image_url' => $product->image_url,
//                             'price' => $product->price,
//                             'stock' => $product->stock
//                         ],
//                         'quantity' => $item['quantity'],
//                         'price' => $item['price'] ?? $product->price,
//                         'subtotal' => ($item['price'] ?? $product->price) * $item['quantity']
//                     ];
//                 } else {
//                     $debug['missing_product'] = $productId;
//                 }
//             }
            
//             $debug['step7_final_cart'] = $cart;
//             $debug['step8_cart_count'] = count($cart);
//             $debug['step9_total'] = $cartService->subtotal();
//         }
        
//         return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
        
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage(),
//             'line' => $e->getLine(),
//             'file' => $e->getFile()
//         ], 500, [], JSON_PRETTY_PRINT);
//     }
// });


// // أضف هذا Route المؤقت في web.php
// Route::get('/quick-add', function () {
//     try {
//         $cartService = app(\App\Services\CartService::class);
//         $product = \App\Models\Product::find(1); // TekWatch Pro Max
        
//         if (!$product) {
//             return ['error' => 'Product not found'];
//         }
        
//         // أضف المنتج للسلة
//         $cartService->add(
//             $product->id,
//             1,
//             $product->price,
//             [
//                 'title' => $product->title,
//                 'image' => $product->image,
//                 'slug' => $product->slug
//             ]
//         );
        
//         return [
//             'success' => true,
//             'message' => 'Product added to cart for logged in user',
//             'user_id' => auth()->id(),
//             'cart_key' => $cartService->getCartKey(),
//             'cart_count' => $cartService->count(),
//             'redirect_to' => '/cart'
//         ];
        
//     } catch (\Exception $e) {
//         return ['error' => $e->getMessage()];
//     }
// });

// // أضف هذا Route في web.php
// Route::get('/fix-cart', function () {
//     try {
//         $userId = auth()->id();
        
//         if (!$userId) {
//             return ['error' => 'User not logged in'];
//         }
        
//         // المفاتيح المختلفة
//         $userKey = 'cart:user_' . $userId;
//         $sessionKey = 'cart:session_' . session()->getId();
        
//         // تحقق من وجود البيانات في مفاتيح مختلفة
//         $userCart = \Illuminate\Support\Facades\Redis::get($userKey);
//         $sessionCart = \Illuminate\Support\Facades\Redis::get($sessionKey);
        
//         // ابحث عن جميع مفاتيح السلة للمستخدم
//         $allKeys = \Illuminate\Support\Facades\Redis::keys('cart:*');
//         $foundCarts = [];
        
//         foreach ($allKeys as $key) {
//             $cartData = \Illuminate\Support\Facades\Redis::get($key);
//             if ($cartData) {
//                 $foundCarts[$key] = json_decode($cartData, true);
//             }
//         }
        
//         // إذا وُجدت سلة في cart:user_4، انسخها للمفتاح الصحيح
//         $correctKey = 'cart:user_' . $userId;
//         if (isset($foundCarts[$correctKey]) && !empty($foundCarts[$correctKey])) {
//             return [
//                 'status' => 'Cart already exists in correct location',
//                 'cart_key' => $correctKey,
//                 'cart_contents' => $foundCarts[$correctKey],
//                 'cart_count' => count($foundCarts[$correctKey])
//             ];
//         }
        
//         // ابحث عن أي سلة تحتوي على منتجات وانقلها
//         foreach ($foundCarts as $key => $cart) {
//             if (!empty($cart) && strpos($key, 'user_' . $userId) !== false) {
//                 // انسخ السلة للمفتاح الصحيح
//                 \Illuminate\Support\Facades\Redis::setex($correctKey, 86400, json_encode($cart));
                
//                 return [
//                     'status' => 'Cart moved successfully',
//                     'from' => $key,
//                     'to' => $correctKey,
//                     'cart_contents' => $cart,
//                     'cart_count' => count($cart)
//                 ];
//             }
//         }
        
//         // إذا لم توجد سلة، أنشئ واحدة جديدة
//         $cartService = app(\App\Services\CartService::class);
//         $product = \App\Models\Product::find(1);
        
//         if ($product) {
//             $cartService->add(
//                 $product->id,
//                 1,
//                 $product->price,
//                 [
//                     'title' => $product->title,
//                     'image' => $product->image,
//                     'slug' => $product->slug
//                 ]
//             );
            
//             return [
//                 'status' => 'New cart created',
//                 'cart_key' => $cartService->getCartKey(),
//                 'cart_contents' => $cartService->get(),
//                 'cart_count' => $cartService->count()
//             ];
//         }
        
//         return [
//             'status' => 'No action taken',
//             'found_carts' => $foundCarts,
//             'user_id' => $userId,
//             'expected_key' => $correctKey
//         ];
        
//     } catch (\Exception $e) {
//         return [
//             'error' => $e->getMessage(),
//             'line' => $e->getLine()
//         ];
//     }
// });


// //test image
// Route::get('/test-images', function() {
//     $products = \App\Models\Product::all();
//     foreach($products as $product) {
//         echo "Product: " . $product->title . "<br>";
//         echo "Image field: " . $product->image . "<br>";
//         echo "Image URL: " . $product->image_url . "<br>";
//         echo "File exists: " . (file_exists(public_path('storage/' . $product->image)) ? 'Yes' : 'No') . "<br><br>";
//     }
// });

// Route::get('/debug-storage', function() {
//     echo "Storage path: " . storage_path('app/public/products') . "<br>";
//     echo "Public storage path: " . public_path('storage/products') . "<br>";
//     echo "Storage link exists: " . (is_link(public_path('storage')) ? 'Yes' : 'No') . "<br>";
//     echo "Products folder exists: " . (is_dir(storage_path('app/public/products')) ? 'Yes' : 'No') . "<br>";
// });

// // أضف هذا Route في web.php للتشخيص
// Route::get('/debug-categories', function () {
//     try {
//         $categories = \App\Models\Category::withCount('products')->get();
        
//         $debug = [
//             'categories_count' => $categories->count(),
//             'categories_data' => $categories->map(function($cat) {
//                 return [
//                     'id' => $cat->id,
//                     'name' => $cat->name,
//                     'slug' => $cat->slug,
//                     'image_field' => $cat->image,
//                     'image_url' => $cat->image_url,
//                     'has_image' => $cat->hasImage(),
//                     'icon' => $cat->icon,
//                     'products_count' => $cat->products_count,
//                     'description' => $cat->description
//                 ];
//             })->toArray()
//         ];
        
//         return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
        
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage(),
//             'line' => $e->getLine(),
//             'file' => $e->getFile()
//         ], 500, [], JSON_PRETTY_PRINT);
//     }
// });


// // أضف هذا Route في web.php لإصلاح مسارات الصور
// Route::get('/fix-category-images', function () {
//     try {
//         $categories = \App\Models\Category::all();
//         $fixed = [];
        
//         foreach ($categories as $category) {
//             if ($category->image) {
//                 // المسار الحالي الخاطئ
//                 $currentPath = $category->image;
                
//                 // استخراج اسم الملف من المسار
//                 $fileName = basename($currentPath);
                
//                 // البحث عن الملف في المجلدات
//                 $possiblePaths = [
//                     "products/{$fileName}.jpg",
//                     "products/{$fileName}.png",
//                     "products/{$fileName}.jpeg",
//                     "categories/{$fileName}.jpg",
//                     "categories/{$fileName}.png",
//                     "categories/{$fileName}.jpeg",
//                     "{$fileName}.jpg",
//                     "{$fileName}.png",
//                     "{$fileName}.jpeg"
//                 ];
                
//                 $foundPath = null;
//                 foreach ($possiblePaths as $path) {
//                     if (file_exists(storage_path('app/public/' . $path))) {
//                         $foundPath = $path;
//                         break;
//                     }
//                 }
                
//                 if ($foundPath) {
//                     // تحديث المسار في قاعدة البيانات
//                     $category->update(['image' => $foundPath]);
//                     $fixed[] = [
//                         'category' => $category->name,
//                         'old_path' => $currentPath,
//                         'new_path' => $foundPath,
//                         'status' => 'fixed'
//                     ];
//                 } else {
//                     $fixed[] = [
//                         'category' => $category->name,
//                         'old_path' => $currentPath,
//                         'new_path' => null,
//                         'status' => 'file_not_found'
//                     ];
//                 }
//             }
//         }
        
//         return response()->json([
//             'message' => 'Image paths fix completed',
//             'results' => $fixed
//         ], 200, [], JSON_PRETTY_PRINT);
        
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage(),
//             'line' => $e->getLine()
//         ], 500, [], JSON_PRETTY_PRINT);
//     }
// });


// // أضف هذا Route في web.php لتحديث قاعدة البيانات مباشرة
// Route::get('/update-category-db', function () {
//     try {
//         // حل مؤقت: استخدام placeholder بدلاً من صورة حقيقية
//         $category = \App\Models\Category::find(3);
        
//         if ($category) {
//             // امسح مسار الصورة الخاطئ
//             $category->image = null;
//             $category->save();
            
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Category image cleared - will use placeholder',
//                 'category' => [
//                     'id' => $category->id,
//                     'name' => $category->name,
//                     'image' => $category->image,
//                     'image_url' => $category->image_url,
//                     'icon' => $category->icon
//                 ]
//             ]);
//         } else {
//             return response()->json(['error' => 'Category not found']);
//         }
        
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage(),
//             'line' => $e->getLine()
//         ], 500);
//     }
// });

// // أضف هذا Route في web.php لإنشاء صورة للتصنيف
// Route::get('/create-category-image', function () {
//     try {
//         // إنشاء مجلد المنتجات إذا لم يكن موجود
//         $productsDir = storage_path('app/public/products');
//         if (!is_dir($productsDir)) {
//             mkdir($productsDir, 0755, true);
//         }
        
//         // رابط صورة Smartwatch من الإنترنت
//         $imageUrl = 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?w=400&h=300&fit=crop';
        
//         // تحميل الصورة
//         $imageContent = file_get_contents($imageUrl);
        
//         if ($imageContent !== false) {
//             // حفظ الصورة
//             $imagePath = $productsDir . '/Smartwatches.jpg';
//             file_put_contents($imagePath, $imageContent);
            
//             // تحديث قاعدة البيانات
//             \App\Models\Category::where('id', 3)->update([
//                 'image' => 'products/Smartwatches.jpg'
//             ]);
            
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Image created and database updated',
//                 'image_path' => $imagePath,
//                 'image_url' => asset('storage/products/Smartwatches.jpg'),
//                 'file_exists' => file_exists($imagePath),
//                 'file_size' => file_exists($imagePath) ? filesize($imagePath) : 0
//             ]);
//         } else {
//             throw new Exception('Failed to download image');
//         }
        
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage(),
//             'line' => $e->getLine()
//         ], 500);
//     }
// });




// // أضف هذا Route في web.php للتشخيص الشامل

// Route::get('/redis-diagnostic', function () {
//     $diagnostic = [];
    
//     try {
//         // 1. فحص اتصال Redis الأساسي
//         $diagnostic['step1_redis_connection'] = 'Testing...';
        
//         $pong = \Illuminate\Support\Facades\Redis::ping();
//         $diagnostic['step1_redis_connection'] = $pong ? 'SUCCESS - Redis Connected' : 'FAILED - No Response';
        
//         // 2. فحص إعدادات Redis في .env
//         $diagnostic['step2_redis_config'] = [
//             'REDIS_HOST' => env('REDIS_HOST'),
//             'REDIS_PORT' => env('REDIS_PORT'),
//             'REDIS_PASSWORD' => env('REDIS_PASSWORD') ? 'SET' : 'NULL',
//             'SESSION_DRIVER' => env('SESSION_DRIVER'),
//             'CACHE_DRIVER' => env('CACHE_DRIVER')
//         ];
        
//         // 3. اختبار كتابة وقراءة من Redis
//         $diagnostic['step3_redis_read_write'] = 'Testing...';
        
//         $testKey = 'test_' . time();
//         $testValue = 'Laravel Redis Test - ' . now();
        
//         \Illuminate\Support\Facades\Redis::set($testKey, $testValue);
//         $retrievedValue = \Illuminate\Support\Facades\Redis::get($testKey);
//         \Illuminate\Support\Facades\Redis::del($testKey);
        
//         $diagnostic['step3_redis_read_write'] = [
//             'written' => $testValue,
//             'read' => $retrievedValue,
//             'match' => $testValue === $retrievedValue ? 'SUCCESS' : 'FAILED'
//         ];
        
//         // 4. فحص CartService
//         $diagnostic['step4_cart_service'] = 'Testing...';
        
//         if (class_exists(\App\Services\CartService::class)) {
//             $cartService = app(\App\Services\CartService::class);
            
//             // امسح السلة أولاً
//             $cartService->clear();
            
//             // اختبر إضافة منتج
//             $cartService->add(999, 2, 25.99, ['title' => 'Test Product']);
            
//             $diagnostic['step4_cart_service'] = [
//                 'service_exists' => 'YES',
//                 'cart_key' => $cartService->getCartKey(),
//                 'items_added' => $cartService->get(),
//                 'count' => $cartService->count(),
//                 'subtotal' => $cartService->subtotal()
//             ];
//         } else {
//             $diagnostic['step4_cart_service'] = 'CartService class not found';
//         }
        
//         // 5. فحص جميع مفاتيح السلة في Redis
//         $diagnostic['step5_existing_carts'] = 'Checking...';
        
//         $allKeys = \Illuminate\Support\Facades\Redis::keys('cart:*');
//         $cartData = [];
        
//         foreach ($allKeys as $key) {
//             $data = \Illuminate\Support\Facades\Redis::get($key);
//             $cartData[$key] = $data ? json_decode($data, true) : null;
//         }
        
//         $diagnostic['step5_existing_carts'] = [
//             'total_cart_keys' => count($allKeys),
//             'cart_keys' => $allKeys,
//             'cart_data' => $cartData
//         ];
        
//         // 6. فحص المستخدم الحالي والجلسة
//         $diagnostic['step6_user_session'] = [
//             'user_logged_in' => auth()->check(),
//             'user_id' => auth()->id(),
//             'session_id' => session()->getId(),
//             'expected_cart_key_if_logged_in' => auth()->check() ? 'cart:user_' . auth()->id() : null,
//             'expected_cart_key_if_guest' => 'cart:session_' . session()->getId()
//         ];
        
//         // 7. فحص المنتجات في قاعدة البيانات
//         $diagnostic['step7_products_check'] = [
//             'total_products' => \App\Models\Product::count(),
//             'first_product' => \App\Models\Product::first() ? [
//                 'id' => \App\Models\Product::first()->id,
//                 'title' => \App\Models\Product::first()->title,
//                 'price' => \App\Models\Product::first()->price
//             ] : 'No products found'
//         ];
        
//         // 8. اختبار كامل للسلة
//         $diagnostic['step8_full_cart_test'] = 'Testing...';
        
//         if (class_exists(\App\Services\CartService::class) && \App\Models\Product::count() > 0) {
//             $cartService = app(\App\Services\CartService::class);
//             $product = \App\Models\Product::first();
            
//             // امسح السلة
//             $cartService->clear();
            
//             // أضف منتج حقيقي
//             $cartService->add($product->id, 1, $product->price, [
//                 'title' => $product->title,
//                 'image' => $product->image
//             ]);
            
//             $diagnostic['step8_full_cart_test'] = [
//                 'product_added' => [
//                     'id' => $product->id,
//                     'title' => $product->title,
//                     'price' => $product->price
//                 ],
//                 'cart_after_add' => $cartService->get(),
//                 'cart_count' => $cartService->count(),
//                 'cart_total' => $cartService->subtotal()
//             ];
//         } else {
//             $diagnostic['step8_full_cart_test'] = 'Cannot test - Missing service or products';
//         }
        
//         // خلاصة النتائج
//         $diagnostic['summary'] = [
//             'redis_working' => isset($diagnostic['step3_redis_read_write']['match']) && $diagnostic['step3_redis_read_write']['match'] === 'SUCCESS',
//             'cart_service_working' => isset($diagnostic['step4_cart_service']['count']) && $diagnostic['step4_cart_service']['count'] > 0,
//             'overall_status' => 'Check individual steps above'
//         ];
        
//     } catch (\Exception $e) {
//         $diagnostic['error'] = [
//             'message' => $e->getMessage(),
//             'file' => $e->getFile(),
//             'line' => $e->getLine()
//         ];
//     }
    
//     return response()->json($diagnostic, 200, [], JSON_PRETTY_PRINT);
// });


// // أضف هذه الـ routes في web.php

// // تنظيف السلة من المنتجات التجريبية
// Route::get('/force-clean-cart', function () {
//     try {
//         $cartService = app(\App\Services\CartService::class);
        
//         // احذف أي منتج ID = 999 باستخدام method صحيحة
//         $cartService->remove('999');
        
//         return [
//             'success' => true,
//             'message' => 'Test product removed successfully',
//             'cart_after_clean' => $cartService->get(),
//             'count' => $cartService->count(),
//             'total_quantity' => $cartService->totalQuantity()
//         ];
        
//     } catch (\Exception $e) {
//         return ['error' => $e->getMessage()];
//     }
// });

// // مسح السلة بالكامل
// Route::get('/clear-entire-cart', function () {
//     try {
//         $cartService = app(\App\Services\CartService::class);
//         $cartService->clear();
        
//         return [
//             'success' => true,
//             'message' => 'Cart cleared completely',
//             'cart_count' => $cartService->count()
//         ];
        
//     } catch (\Exception $e) {
//         return ['error' => $e->getMessage()];
//     }
// });

// // إضافة منتج واحد فقط للاختبار
// Route::get('/add-single-product', function () {
//     try {
//         $cartService = app(\App\Services\CartService::class);
        
//         // امسح السلة أولاً
//         $cartService->clear();
        
//         // أضف منتج واحد
//         $product = \App\Models\Product::first();
        
//         if (!$product) {
//             return ['error' => 'No products found'];
//         }
        
//         $cartService->add($product->id, 1, $product->price, [
//             'title' => $product->title,
//             'image' => $product->image
//         ]);
        
//         return [
//             'success' => true,
//             'message' => 'Single product added',
//             'product' => [
//                 'id' => $product->id,
//                 'title' => $product->title,
//                 'price' => $product->price
//             ],
//             'cart_count' => $cartService->count(),
//             'total_quantity' => $cartService->totalQuantity(),
//             'cart_contents' => $cartService->get()
//         ];
        
//     } catch (\Exception $e) {
//         return ['error' => $e->getMessage()];
//     }
// });

// Route::get('/test-add-from-shop', function () {
//     try {
//         $product = \App\Models\Product::find(16); // أو أي منتج آخر
//         $cartService = app(\App\Services\CartService::class);
        
//         $cartService->add($product->id, 1, $product->price, [
//             'title' => $product->title,
//             'image' => $product->image
//         ]);
        
//         return [
//             'success' => true,
//             'message' => 'Product added via route test',
//             'cart_count' => $cartService->count(),
//             'cart_contents' => $cartService->get()
//         ];
//     } catch (\Exception $e) {
//         return ['error' => $e->getMessage()];
//     }
// });