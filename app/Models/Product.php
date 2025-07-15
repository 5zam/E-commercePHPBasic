<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $fillable = [
        'category_id',
        'title',
        'slug',
        'price',
        'description',
        'stock',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-product.png');
    }
}
