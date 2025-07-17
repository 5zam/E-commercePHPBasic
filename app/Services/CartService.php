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
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'attributes' => $attributes,
                'added_at' => now()->timestamp
            ];
        }
        
        $this->save($cart);
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

    protected function save($cart)
    {
        $cartKey = $this->getCartKeyInternal();
        Redis::setex($cartKey, $this->ttl, json_encode($cart));
    }

    public function count()
    {
        return count($this->get());
    }

    public function subtotal()
    {
        $cart = $this->get();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getCartKey()
    {
        return $this->getCartKeyInternal();
    }

    
    public function update($productId, $quantity) { /* ... */ }
    public function remove($productId) { /* ... */ }
    public function clear() { /* ... */ }
}