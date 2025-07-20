@extends('layout.app')

@section('title', 'Order Success - TekSouq')

@push('styles')
<link href="{{ asset('css/success.css') }}" rel="stylesheet">
@endpush

@section('content')
{{-- Success Hero - نفس تدرج صفحة Shop --}}
<div class="success-hero">
    <div class="success-hero-background"></div>
    <div class="success-hero-overlay"></div>
    <div class="container">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Order Placed Successfully!</h1>
            <p class="success-subtitle">Thank you for your purchase. Your order is being processed.</p>
        </div>
    </div>
</div>

{{-- Order Details --}}
<div class="order-details">
    <div class="container">
        <div class="row">
            {{-- Order Items --}}
            <div class="col-lg-8">
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-number">Order #{{ $order->order_number }}</div>
                            <small class="text-muted">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</small>
                        </div>
                        <div class="order-status">{{ $order->status }}</div>
                    </div>

                    {{-- Order Items --}}
                    <div class="order-items">
                        @foreach($order->items as $item)
                        <div class="order-item">
                            <div class="item-image">
                                <img src="{{ $item->product_image_url }}" alt="{{ $item->product_title }}">
                            </div>
                            <div class="item-details">
                                <div class="item-name">{{ $item->product_title }}</div>
                                <div class="item-meta">
                                    Quantity: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}
                                </div>
                            </div>
                            <div class="item-total">
                                ${{ number_format($item->subtotal, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Shipping Information --}}
                <div class="shipping-info">
                    <div class="info-section">
                        <div class="info-title">
                            <i class="fas fa-user"></i>
                            Customer Information
                        </div>
                        <div class="info-content">
                            <strong>{{ $order->full_name }}</strong><br>
                            {{ $order->email }}<br>
                            {{ $order->phone }}
                        </div>
                    </div>

                    <div class="info-section">
                        <div class="info-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Shipping Address
                        </div>
                        <div class="info-content">
                            {{ $order->address }}<br>
                            {{ $order->city }}
                            @if($order->postal_code), {{ $order->postal_code }}@endif
                        </div>
                    </div>

                    <div class="info-section">
                        <div class="info-title">
                            <i class="fas fa-truck"></i>
                            Shipping Method
                        </div>
                        <div class="info-content">
                            {{ ucwords(str_replace('_', ' ', $order->shipping_method)) }}
                            @if($order->shipping_cost > 0)
                                - ${{ number_format($order->shipping_cost, 2) }}
                            @else
                                - Free
                            @endif
                        </div>
                    </div>

                    @if($order->notes)
                    <div class="info-section">
                        <div class="info-title">
                            <i class="fas fa-sticky-note"></i>
                            Special Instructions
                        </div>
                        <div class="info-content">
                            {{ $order->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="price-summary">
                    <h3 class="summary-title">
                        <i class="fas fa-receipt me-2"></i>
                        Order Summary
                    </h3>
                    
                    <div class="price-row">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    
                    <div class="price-row">
                        <span>Shipping:</span>
                        <span>
                            @if($order->shipping_cost > 0)
                                ${{ number_format($order->shipping_cost, 2) }}
                            @else
                                Free
                            @endif
                        </span>
                    </div>
                    
                    <div class="price-row">
                        <span>Tax:</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    
                    <div class="price-row total">
                        <span>Total:</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="action-buttons">
            <a href="{{ route('shop') }}" class="btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>
                Continue Shopping
            </a>
            <a href="{{ route('home') }}" class="btn-secondary">
                <i class="fas fa-home me-2"></i>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection