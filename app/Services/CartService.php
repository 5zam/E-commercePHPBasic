<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected $cartKey = null;
    protected $ttl = 86400;

    protected function getCartKeyInternal()
    {
        if ($this->cartKey === null) {
            if (!session()->isStarted()) {
                session()->start();
            }
            
            if (Auth::check() && Auth::id()) {
                $identifier = 'user_' . Auth::id();
            } else {
                $identifier = 'session_' . session()->getId();
            }
            
            $this->cartKey = 'cart:' . $identifier;
        }
        
        return $this->cartKey;
    }

    public function add($productId, $quantity = 1, $price = null, $attributes = [])
    {
        $cart = $this->get();
        
        // تأكد أن الـ productId و quantity صحيحين
        $productId = (string)$productId;
        $quantity = (int)$quantity;
        
        if (isset($cart[$productId])) {
            // إذا المنتج موجود، زود الكمية
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // منتج جديد
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => (float)$price,
                'attributes' => $attributes,
                'added_at' => now()->timestamp
            ];
        }
        
        $this->save($cart);
        
        // لوق للتشخيص
        \Log::info('Cart add operation', [
            'product_id' => $productId,
            'quantity' => $quantity,
            'cart_after_add' => $cart,
            'total_items' => count($cart),
            'total_quantity' => $this->totalQuantity()
        ]);
        
        return $cart[$productId];
    }

    public function get()
    {
        try {
            $cartKey = $this->getCartKeyInternal();
            $cart = Redis::get($cartKey);
            return $cart ? json_decode($cart, true) : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function save($cart)
    {
        $cartKey = $this->getCartKeyInternal();
        Redis::setex($cartKey, $this->ttl, json_encode($cart));
    }

    public function count()
    {
        $cart = $this->get();
        return count($cart); // عدد المنتجات المختلفة
    }

    public function totalQuantity()
    {
        $cart = $this->get();
        $totalQty = 0;
        foreach ($cart as $item) {
            $totalQty += (int)$item['quantity'];
        }
        return $totalQty; // مجموع الكميات
    }

    public function update($productId, $quantity)
    {
        $cart = $this->get();
        
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
            $this->save($cart);
        }
        
        return $cart;
    }

    public function remove($productId)
    {
        $cart = $this->get();
        unset($cart[$productId]);
        $this->save($cart);
        return $cart;
    }

    public function clear()
    {
        $cartKey = $this->getCartKeyInternal();
        Redis::del($cartKey);
    }

    public function subtotal()
    {
        $cart = $this->get();
        $total = 0;
        foreach ($cart as $item) {
            $total += (float)$item['price'] * (int)$item['quantity'];
        }
        return $total;
    }

    public function getCartKey()
    {
        return $this->getCartKeyInternal();
    }

    public function has($productId)
    {
        $cart = $this->get();
        return isset($cart[$productId]);
    }

    // Debug method
    public function getDebugInfo()
    {
        return [
            'cart_key' => $this->getCartKeyInternal(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'cart_items' => $this->get(),
            'cart_count' => $this->count(),
            'cart_total' => $this->subtotal()
        ];
    }
}