<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
     public function index(Request $request)
    {
        $query = Product::query();

        // Search functionality - Fixed field names
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search); // Using scope from model
        }

        // Price range filter
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Sort options
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Add with category if exists
        $query->with('category');

        $products = $query->paginate(12)->appends(request()->query());
        
        // Simple filter options
        $categories = collect([]);
        $brands = collect([]);
        $priceRange = [
            'min' => 0,
            'max' => 500
        ];

        return view('shop.index', compact(
            'products', 
            'categories', 
            'brands', 
            'priceRange'
        ));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        // Add to recently viewed
        $this->addToRecentlyViewed($product->id);

        return view('shop.product', compact('product', 'relatedProducts'));
    }

    public function category($category)
    {
        $products = Product::whereHas('category', function($query) use ($category) {
                $query->where('slug', $category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categoryName = ucfirst(str_replace('-', ' ', $category));

        return view('shop.category', compact('products', 'category', 'categoryName'));
    }

    public function apiSearch(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::search($query)
            ->take(5)
            ->get(['id', 'title', 'price', 'image']);

        // Format for API response
        $results = $products->map(function($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->formatted_price,
                'image' => $product->image_url,
                'url' => route('product.show', $product->id)
            ];
        });

        return response()->json($results);
    }

    public function quickView($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'html' => view('shop.partials.quick-view', compact('product'))->render()
        ]);
    }

    /**
     * Debug images - Temporary function to check image paths
     */
    public function debugImages()
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $products = Product::all();
        $imageInfo = [];

        foreach ($products as $product) {
            $info = [
                'id' => $product->id,
                'title' => $product->title,
                'image_field' => $product->image,
                'image_url' => $product->image_url,
                'has_image' => $product->hasImage(),
                'possible_paths' => []
            ];

            if ($product->image) {
                $paths = [
                    'storage/' . $product->image,
                    $product->image,
                    'storage/products/' . $product->image,
                    'storage/uploads/' . $product->image,
                ];

                foreach ($paths as $path) {
                    $fullPath = public_path($path);
                    $info['possible_paths'][$path] = [
                        'exists' => file_exists($fullPath),
                        'full_path' => $fullPath,
                        'url' => asset($path)
                    ];
                }
            }

            $imageInfo[] = $info;
        }

        return response()->json($imageInfo, 200, [], JSON_PRETTY_PRINT);
    }

    private function addToRecentlyViewed($productId)
    {
        $recentlyViewed = session()->get('recently_viewed', []);
        
        // Remove if already exists
        $recentlyViewed = array_filter($recentlyViewed, fn($id) => $id != $productId);
        
        // Add to beginning
        array_unshift($recentlyViewed, $productId);
        
        // Keep only last 10
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);
        
        session()->put('recently_viewed', $recentlyViewed);
    }
}