<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
   
    /**
     * Display checkout page
     */
    public function index()
    {
        // استخدم CartService بدلاً من session
        $cartItems = $this->cartService->get();
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        // تحويل بيانات Redis لتنسيق Blade
        $products = \App\Models\Product::whereIn('id', array_keys($cartItems))->get();
        
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
        
        return view('checkout.index', compact('cart'));
    }
    
    /**
     * Process the checkout form
     */
    public function process(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'shipping_method' => 'required|in:standard,express,overnight',
            'notes' => 'nullable|string|max:1000'
        ]);
        
        // استخدم CartService بدلاً من session
        $cartItems = $this->cartService->get();
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        // تحويل بيانات Redis للمعالجة
        $products = \App\Models\Product::whereIn('id', array_keys($cartItems))->get();
        
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
        
        // Calculate totals
        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $shippingCost = $this->getShippingCost($validated['shipping_method']);
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $shippingCost + $tax;
        
        // Generate order number
        $orderNumber = 'TK' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Prepare order data
        $orderData = [
            'order_number' => $orderNumber,
            'customer_info' => [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone']
            ],
            'shipping_info' => [
                'address' => $validated['address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'] ?? '',
                'method' => $validated['shipping_method'],
                'cost' => $shippingCost
            ],
            'order_items' => $cart,
            'pricing' => [
                'subtotal' => $subtotal,
                'shipping' => $shippingCost,
                'tax' => $tax,
                'total' => $total
            ],
            'notes' => $validated['notes'] ?? '',
            'status' => 'pending',
            'created_at' => now()
        ];
        
        // Save order to session (in real app, save to database)
        session(['last_order' => $orderData]);
        
        // امسح السلة من Redis
        $this->cartService->clear();
        
        // Redirect to success page
        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    }
    
    /**
     * Show order success page
     */
    public function success()
    {
        $order = session('last_order');
        
        if (!$order) {
            return redirect()->route('shop')->with('error', 'No order found');
        }
        
        return view('checkout.success', compact('order'));
    }
    
    /**
     * Calculate shipping cost based on method
     */
    private function getShippingCost($method)
    {
        switch ($method) {
            case 'express':
                return 15.00;
            case 'overnight':
                return 35.00;
            case 'standard':
            default:
                return 0.00;
        }
    }
}