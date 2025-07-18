<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;


class Product extends Model
{
  use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'price',
        'description',
        'stock',
        'image',
    ];

    protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the full image URL - Compatible with Voyager
     */

  public function getImageUrlAttribute(): string
{
    if ($this->image) {
        // External URLs
        if (strpos($this->image, 'http') === 0) {
            return $this->image;
        }
        
        // Local files - simple path
        return url('storage/' . $this->image);
    }
    
    return 'https://via.placeholder.com/400x400/4f46e5/ffffff?text=TekSouq';
}
    // public function getImageUrlAttribute(): string
    // {
    //     if ($this->image) {
    //         // Check different possible paths for Voyager
    //         $possiblePaths = [
    //             // Voyager default path
    //             'storage/' . $this->image,
    //             // Direct storage path
    //             $this->image,
    //             // Products specific folder
    //             'storage/products/' . $this->image,
    //             // Upload folder
    //             'storage/uploads/' . $this->image,
    //         ];

    //         foreach ($possiblePaths as $path) {
    //             $fullPath = public_path($path);
    //             if (file_exists($fullPath)) {
    //                 return asset($path);
    //             }
    //         }

    //         // If image starts with http, return as is
    //         if (str_starts_with($this->image, 'http')) {
    //             return $this->image;
    //         }

    //         // Try Voyager asset function if available
    //         if (function_exists('Voyager')) {
    //             try {
    //                 return Voyager::image($this->image);
    //             } catch (\Exception $e) {
    //                 // Continue to fallback
    //             }
    //         }

    //         // Last attempt - direct asset
    //         return asset('storage/' . $this->image);
    //     }
        
    //     // Fallback placeholder - better quality
    //     return $this->getPlaceholderImage();
    // }

    /**
     * Get a nice placeholder image
     */
    private function getPlaceholderImage(): string
    {
        $colors = [
            '4f46e5', // Purple
            '06b6d4', // Cyan  
            '10b981', // Green
            'f59e0b', // Yellow
            'ef4444', // Red
        ];
        
        $color = $colors[abs(crc32($this->title ?? 'product')) % count($colors)];
        $title = urlencode($this->title ?? 'TekSouq Product');
        
        return "https://via.placeholder.com/400x400/{$color}/ffffff?text={$title}";
    }


//     public function getImageUrlAttribute(): string
// {
//     if ($this->image) {
//         // External URLs
//         if (strpos($this->image, 'http') === 0) {
//             return $this->image;
//         }
        
//         // Local files - simple path
//         return asset('storage/' . $this->image);
//     }
    
//     // Fallback
//     return 'https://via.placeholder.com/400x400/4f46e5/ffffff?text=TekSouq';
// }
    /**
     * Check if product has valid image
     */
    public function hasImage(): bool
    {
        if (!$this->image) {
            return false;
        }

        // Check if file exists
        $path = public_path('storage/' . $this->image);
        return file_exists($path);
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock > 10) {
            return 'in_stock';
        } elseif ($this->stock > 0) {
            return 'low_stock';
        } else {
            return 'out_of_stock';
        }
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%");
    }

    /**
     * Scope for available products
     */
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope for price range
     */
    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        
        if ($max) {
            $query->where('price', '<=', $max);
        }
        
        return $query;
    }
}