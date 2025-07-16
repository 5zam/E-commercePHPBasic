<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
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

        $products = $query->paginate(12)->appends(request()->query());
        
        // Simple filter options (no database dependency)
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
        $product = Product::findOrFail($id);

        // Get related products (same category, excluding current product)
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('stock_quantity', '>', 0)
            ->take(4)
            ->get();

        // Add current product to recently viewed
        $this->addToRecentlyViewed($product->id);

        return view('shop.product', compact('product', 'relatedProducts'));
    }

    public function category($category)
    {
        $products = Product::where('category', $category)
            ->orderBy('is_featured', 'desc')
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

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->take(5)
            ->get(['id', 'name', 'price', 'image']);

        return response()->json($products);
    }

    public function quickView($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'html' => view('shop.partials.quick-view', compact('product'))->render()
        ]);
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