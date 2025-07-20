<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'session_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'shipping_method',
        'shipping_cost',
        'subtotal',
        'tax',
        'total',
        'notes',
        'status',
        'placed_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'placed_at' => 'datetime'
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

   
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'paid' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

   
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'TK' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
