<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

   
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    public function getProductTitleAttribute()
    {
        return $this->product ? $this->product->title : 'Product Not Found';
    }

    public function getProductImageUrlAttribute()
    {
        return $this->product ? $this->product->image_url : asset('images/placeholder.jpg');
    }
}
