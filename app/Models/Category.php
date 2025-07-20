<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image',
    ];

    protected $appends = ['image_url'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
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
            
            // Fix malformed paths from Voyager
            $imagePath = $this->image;
            
            // If path contains full storage path, extract the relative part
            if (strpos($imagePath, 'storage\\app\\public\\') !== false) {
                $imagePath = str_replace('storage\\app\\public\\', '', $imagePath);
                $imagePath = str_replace('\\', '/', $imagePath);
            }
            
            // If path contains storage/app/public/, extract the relative part
            if (strpos($imagePath, 'storage/app/public/') !== false) {
                $imagePath = str_replace('storage/app/public/', '', $imagePath);
            }
            
            // Local files - simple path
            return url('storage/' . $imagePath);
        }
        
        // Default placeholder for categories
        return 'https://via.placeholder.com/400x300/4f46e5/ffffff?text=' . urlencode($this->name ?? 'Category');
    }

    /**
     * Check if category has valid image
     */
    public function hasImage(): bool
    {
        if (!$this->image) {
            return false;
        }

        // Fix malformed paths first
        $imagePath = $this->image;
        
        // If path contains full storage path, extract the relative part
        if (strpos($imagePath, 'storage\\app\\public\\') !== false) {
            $imagePath = str_replace('storage\\app\\public\\', '', $imagePath);
            $imagePath = str_replace('\\', '/', $imagePath);
        }
        
        // If path contains storage/app/public/, extract the relative part
        if (strpos($imagePath, 'storage/app/public/') !== false) {
            $imagePath = str_replace('storage/app/public/', '', $imagePath);
        }

        // Check if file exists
        $path = public_path('storage/' . $imagePath);
        return file_exists($path);
    }

    /**
     * Get category icon based on name
     */
    public function getIconAttribute(): string
    {
        $icons = [
            'smartwatch' => 'watch',
            'smartwatches' => 'watch',
            'accessories' => 'plus-circle',
            'electronics' => 'mobile-alt',
            'tech' => 'laptop',
            'gadgets' => 'tablet-alt',
            'default' => 'tag'
        ];

        $name = strtolower($this->name ?? '');
        
        foreach ($icons as $keyword => $icon) {
            if (strpos($name, $keyword) !== false) {
                return $icon;
            }
        }

        return $icons['default'];
    }

    /**
     * Scope for parent categories only
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for categories with products
     */
    public function scopeWithProducts($query)
    {
        return $query->has('products');
    }
}