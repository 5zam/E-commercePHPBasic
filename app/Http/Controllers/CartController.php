<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
     protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

   public function index()
{
    try {
        // Debug info محسن
        // $debugInfo = $this->cartService->getDebugInfo();
        
        $cartItems = $this->cartService->get();
        
        $debugInfo['step2_cart_items'] = $cartItems;
        $debugInfo['step3_is_empty'] = empty($cartItems);
        
        if (empty($cartItems)) {
            return view('cart.index', [
                'cart' => [],
                'total' => 0,
                'count' => 0,
                'debug_info' => $debugInfo
            ]);
        }
        
        $productIds = array_keys($cartItems);
        $products = Product::whereIn('id', $productIds)->get();
        
        $debugInfo['step4_products_found'] = $products->count();
        
        $cart = [];
        foreach ($cartItems as $productId => $item) {
            $product = $products->find($productId);
            
            if ($product) {
                $cart[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] ?? $product->price,
                    'subtotal' => ($item['price'] ?? $product->price) * $item['quantity']
                ];
            }
        }

        $debugInfo['step5_final_cart_count'] = count($cart);

        return view('cart.index', [
            'cart' => $cart,
            'total' => $this->cartService->subtotal(),
            'count' => $this->cartService->count(),
            'debug_info' => $debugInfo
        ]);
        
    } catch (\Exception $e) {
        return view('cart.index', [
            'cart' => [],
            'total' => 0,
            'count' => 0,
            'error' => $e->getMessage(),
            'debug_info' => [
                'error' => true,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]
        ]);
    }
}

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product is out of stock!');
        }

        $quantity = $request->quantity ?? 1;
        
        if ($quantity > $product->stock) {
            return redirect()->back()->with('error', "Only {$product->stock} items available in stock!");
        }

        $this->cartService->add(
            $product->id,
            $quantity,
            $product->price,
            [
                'title' => $product->title,
                'image' => $product->image,
                'slug' => $product->slug
            ]
        );

        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:10'
        ]);

        $quantity = $request->quantity;
        $this->cartService->update($productId, $quantity);

        if ($quantity == 0) {
            return redirect()->back()->with('success', 'Item removed from cart!');
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function remove($productId)
    {
        $this->cartService->remove($productId);
        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        $this->cartService->clear();
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    public function count()
    {
        return response()->json([
            'count' => $this->cartService->count(),
            'total_quantity' => $this->cartService->totalQuantity()
        ]);
    }

    public function items()
    {
        $cartItems = $this->cartService->get();
        $products = Product::whereIn('id', array_keys($cartItems))->get();

        $cart = [];
        foreach ($cartItems as $productId => $item) {
            $product = $products->find($productId);
            if ($product) {
                $cart[] = [
                    'id' => $product->id,
                    'title' => $product->title,
                    'image' => $product->image_url,
                    'price' => $item['price'] ?? $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => ($item['price'] ?? $product->price) * $item['quantity']
                ];
            }
        }

        return response()->json([
            'items' => $cart,
            'total' => $this->cartService->subtotal(),
            'count' => $this->cartService->count()
        ]);
    }
}