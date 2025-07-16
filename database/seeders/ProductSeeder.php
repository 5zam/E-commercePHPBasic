<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'TekWatch Pro Max',
                'description' => 'The ultimate smartwatch experience with advanced health monitoring, GPS tracking, and premium materials. Features include ECG monitoring, blood oxygen sensing, sleep tracking, and 7-day battery life.',
                'short_description' => 'Premium smartwatch with advanced health monitoring and 7-day battery life.',
                'price' => 399.99,
                'sale_price' => 349.99,
                'sku' => 'TW-PRO-MAX-001',
                'stock_quantity' => 25,
                'category' => 'smartwatch',
                'brand' => 'tekwatch',
                'features' => json_encode([
                    'ECG Monitoring',
                    'Blood Oxygen Sensor',
                    'GPS Tracking',
                    'Water Resistant (50m)',
                    '7-Day Battery Life',
                    'Always-On Display',
                    'Wireless Charging'
                ]),
                'specifications' => json_encode([
                    'Display' => '1.9" OLED',
                    'Resolution' => '484x396 pixels',
                    'Battery' => '7 days typical use',
                    'Storage' => '32GB',
                    'Connectivity' => 'Bluetooth 5.0, WiFi, GPS',
                    'Water Rating' => '5ATM + IP68',
                    'Dimensions' => '45mm x 38mm x 10.7mm'
                ]),
                'image' => 'products/tekwatch-pro-max.jpg',
                'gallery' => json_encode([
                    'products/tekwatch-pro-max-1.jpg',
                    'products/tekwatch-pro-max-2.jpg',
                    'products/tekwatch-pro-max-3.jpg'
                ]),
                'is_featured' => true,
                'rating' => 4.8,
                'reviews_count' => 127,
                'meta_title' => 'TekWatch Pro Max - Premium Smartwatch | TekSouq',
                'meta_description' => 'Experience the ultimate in wearable technology with TekWatch Pro Max. Advanced health monitoring, GPS, and 7-day battery life.'
            ],
            [
                'name' => 'TekWatch Sport Edition',
                'description' => 'Built for athletes and fitness enthusiasts. Features comprehensive workout tracking, heart rate monitoring, and rugged design that can withstand the toughest conditions.',
                'short_description' => 'Rugged fitness smartwatch for athletes and active lifestyles.',
                'price' => 299.99,
                'sale_price' => null,
                'sku' => 'TW-SPORT-001',
                'stock_quantity' => 40,
                'category' => 'smartwatch',
                'brand' => 'tekwatch',
                'features' => json_encode([
                    '100+ Workout Modes',
                    'Heart Rate Monitoring',
                    'Military-Grade Durability',
                    'GPS + GLONASS',
                    '14-Day Battery Life',
                    'Sleep Tracking',
                    'Stress Monitoring'
                ]),
                'specifications' => json_encode([
                    'Display' => '1.4" MIP',
                    'Battery' => '14 days GPS off',
                    'Water Rating' => '10ATM',
                    'Weight' => '52g',
                    'Materials' => 'Titanium alloy',
                    'Sensors' => 'Heart rate, SpO2, Accelerometer, Gyroscope'
                ]),
                'image' => 'products/tekwatch-sport.jpg',
                'gallery' => json_encode([]),
                'is_featured' => true,
                'rating' => 4.6,
                'reviews_count' => 89,
                'meta_title' => null,
                'meta_description' => null
            ],
            [
                'name' => 'TekWatch Classic',
                'description' => 'Timeless design meets modern technology. Perfect balance of style and functionality for everyday wear. Features smart notifications, health tracking, and elegant design.',
                'short_description' => 'Elegant smartwatch with timeless design and smart features.',
                'price' => 249.99,
                'sale_price' => 199.99,
                'sku' => 'TW-CLASSIC-001',
                'stock_quantity' => 35,
                'category' => 'smartwatch',
                'brand' => 'tekwatch',
                'features' => json_encode([
                    'Classic Design',
                    'Smart Notifications',
                    'Health Tracking',
                    'Music Control',
                    '5-Day Battery Life',
                    'Customizable Watch Faces'
                ]),
                'specifications' => json_encode([
                    'Display' => '1.3" AMOLED',
                    'Battery' => '5-7 days',
                    'Connectivity' => 'Bluetooth 5.0',
                    'Compatibility' => 'iOS & Android',
                    'Weight' => '45g'
                ]),
                'image' => 'products/tekwatch-classic.jpg',
                'gallery' => json_encode([]),
                'is_featured' => false,
                'rating' => 4.4,
                'reviews_count' => 156,
                'meta_title' => null,
                'meta_description' => null
            ],
            [
                'name' => 'TekWatch Lite',
                'description' => 'Essential smartwatch features at an accessible price. Perfect for those new to wearable technology or looking for basic smart functionality.',
                'short_description' => 'Affordable smartwatch with essential features for everyday use.',
                'price' => 149.99,
                'sale_price' => 129.99,
                'sku' => 'TW-LITE-001',
                'stock_quantity' => 60,
                'category' => 'smartwatch',
                'brand' => 'tekwatch',
                'features' => json_encode([
                    'Basic Health Tracking',
                    'Smart Notifications',
                    'Step Counter',
                    '3-Day Battery Life',
                    'Water Resistant'
                ]),
                'specifications' => json_encode([
                    'Display' => '1.1" LCD',
                    'Battery' => '3-5 days',
                    'Water Rating' => 'IP67',
                    'Weight' => '35g'
                ]),
                'image' => 'products/tekwatch-lite.jpg',
                'gallery' => json_encode([]),
                'is_featured' => true,
                'rating' => 4.2,
                'reviews_count' => 203,
                'meta_title' => null,
                'meta_description' => null
            ],
            [
                'name' => 'FitTracker Pro',
                'description' => 'Advanced fitness tracker with comprehensive health monitoring. Track your workouts, sleep patterns, stress levels, and more with precision accuracy.',
                'short_description' => 'Professional fitness tracker with advanced health monitoring.',
                'price' => 179.99,
                'sale_price' => null,
                'sku' => 'FT-PRO-001',
                'stock_quantity' => 45,
                'category' => 'fitness-tracker',
                'brand' => 'tekwatch',
                'features' => json_encode([
                    '24/7 Heart Rate',
                    'Sleep Analysis',
                    'Stress Monitoring',
                    '50+ Exercise Modes',
                    '10-Day Battery',
                    'SpO2 Monitoring'
                ]),
                'specifications' => json_encode([
                    'Display' => '1.1" Color OLED',
                    'Battery' => '10 days',
                    'Water Rating' => '5ATM',
                    'Sensors' => 'HR, SpO2, 3-axis accelerometer'
                ]),
                'image' => 'products/fittracker-pro.jpg',
                'gallery' => json_encode([]),
                'is_featured' => false,
                'rating' => 4.5,
                'reviews_count' => 92,
                'meta_title' => null,
                'meta_description' => null
            ],
            [
                'name' => 'Wireless Charging Dock',
                'description' => 'Premium wireless charging station for your TekWatch. Fast charging technology with elegant design that complements your workspace.',
                'short_description' => 'Fast wireless charging dock with premium materials.',
                'price' => 79.99,
                'sale_price' => 59.99,
                'sku' => 'ACC-CHARGE-001',
                'stock_quantity' => 100,
                'category' => 'chargers',
                'brand' => 'tekwatch',
                'features' => json_encode([
                    'Fast Charging',
                    'LED Indicator',
                    'Non-slip Base',
                    'Universal Compatibility',
                    'Overheat Protection'
                ]),
                'specifications' => json_encode([
                    'Output' => '5W Fast Charging',
                    'Input' => 'USB-C',
                    'Materials' => 'Aluminum alloy',
                    'Dimensions' => '85mm x 85mm x 25mm'
                ]),
                'image' => 'products/charging-dock.jpg',
                'gallery' => json_encode([]),
                'is_featured' => false,
                'rating' => 4.3,
                'reviews_count' => 67,
                'meta_title' => null,
                'meta_description' => null
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}