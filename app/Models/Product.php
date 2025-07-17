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
    if ($this->image) {
        $imagePath = public_path('storage/' . $this->image);
        
        // إذا كانت الصورة موجودة
        if (file_exists($imagePath)) {
            return asset('storage/' . $this->image);
        }
    }
    
    // صورة افتراضية
    return 'https://via.placeholder.com/300x300/4f46e5/ffffff?text=TekSouq';
}
}
