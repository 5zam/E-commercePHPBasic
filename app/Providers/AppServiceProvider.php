<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
     public function register(): void
    {
        // Register CartService as singleton
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
