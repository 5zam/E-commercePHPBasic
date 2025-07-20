<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Models\Order;
use App\Models\OrderItem;

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
        
        $cartItems = $this->cartService->get();
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        
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
        
        
        $cartItems = $this->cartService->get();
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        
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
        
        
        $order = Order::create([
            'user_id' => auth()->id(),
            'session_id' => auth()->guest() ? session()->getId() : null,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'shipping_method' => $validated['shipping_method'],
            'shipping_cost' => $shippingCost,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'notes' => $validated['notes'],
            'status' => 'pending',
            'placed_at' => now()
        ]);
        
        //save order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price']
            ]);
        }
        
        // Clear the cart
        $this->cartService->clear();
        
        // Redirect to success page مع Order ID
        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Order placed successfully!');
    }
    
    /**
     * Show order success page
     */
    public function success($orderId)
    {
        $order = Order::with('items.product')->find($orderId);
        
        if (!$order) {
            return redirect()->route('shop')->with('error', 'Order not found');
        }
        
        // Check if the user is authorized to view this order
        if (auth()->check()) {
            if ($order->user_id !== auth()->id()) {
                return redirect()->route('shop')->with('error', 'Unauthorized access');
            }
        } else {
            if ($order->session_id !== session()->getId()) {
                return redirect()->route('shop')->with('error', 'Unauthorized access');
            }
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