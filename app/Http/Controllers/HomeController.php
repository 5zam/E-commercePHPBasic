<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    // public function index()
    // {
    //     // Get featured products (limit to 8 for homepage)
    //     $products = Product::where('is_featured', true)
    //                       ->orWhere('status', 'active')
    //                       ->limit(8)
    //                       ->orderBy('created_at', 'desc')
    //                       ->get();

    //     // Get categories for the category section
    //     $categories = Category::where('is_active', true)
    //                         ->limit(4)
    //                         ->get();

    //     // Get some statistics for the page
    //     $stats = [
    //         'total_products' => Product::where('status', 'active')->count(),
    //         'total_categories' => Category::where('is_active', true)->count(),
    //         'featured_products' => Product::where('is_featured', true)->count(),
    //     ];

    //     return view('home', compact('products', 'categories', 'stats'));
    // }

    /**
     * Handle newsletter subscription
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email'
        ]);

        // Create newsletter subscription (you'll need to create this model/table)
        // NewsletterSubscription::create(['email' => $request->email]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    }

    /**
     * Handle product search
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        
        $products = Product::where('status', 'active')
                          ->where(function($q) use ($query) {
                              $q->where('name', 'LIKE', "%{$query}%")
                                ->orWhere('description', 'LIKE', "%{$query}%")
                                ->orWhere('sku', 'LIKE', "%{$query}%");
                          })
                          ->paginate(12);

        return view('products.search', compact('products', 'query'));
    }
}

