@extends('layout.app')

@section('title', 'Privacy Policy - TekSouq')

@push('styles')
<link href="{{ asset('css/policy.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Policy Hero Section -->
<section class="policy-hero">
    <div class="policy-hero-background"></div>
    <div class="policy-hero-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="policy-hero-content">
                    <h1 class="policy-hero-title">
                        Privacy <span class="gradient-text">Policy</span>
                    </h1>
                    <p class="policy-hero-subtitle">
                        Your privacy matters to us. Learn how we protect and handle your personal information.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Policy Content -->
<section class="policy-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="policy-card">
                    
                    <!-- Introduction -->
                    <div class="policy-section">
                        <p class="policy-intro">
                            Welcome to TekSouq. This Privacy Policy explains how we collect, use, and protect your information when you use our ecommerce platform. We are committed to ensuring your privacy and maintaining the security of your personal data.
                        </p>
                    </div>

                    <!-- Information We Collect -->
                    <div class="policy-section">
                        <h2 class="policy-section-title">Information We Collect</h2>
                        <div class="policy-text">
                            <p>We collect information you provide directly to us, including:</p>
                            <ul class="policy-list">
                                <li>Personal details when you create an account (name, email, phone number)</li>
                                <li>Billing and shipping information for order processing</li>
                                <li>Communication preferences and customer service interactions</li>
                                <li>Device and browser information for site optimization</li>
                            </ul>
                        </div>
                    </div>

                    <!-- How We Use Your Information -->
                    <div class="policy-section">
                        <h2 class="policy-section-title">How We Use Your Information</h2>
                        <div class="policy-text">
                            <p>We use the information we collect to:</p>
                            <ul class="policy-list">
                                <li>Process and fulfill your orders efficiently</li>
                                <li>Provide customer support and respond to inquiries</li>
                                <li>Send important updates about your orders and account</li>
                                <li>Improve our website functionality and user experience</li>
                                <li>Comply with legal obligations and protect against fraud</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Information Sharing -->
                    <div class="policy-section">
                        <h2 class="policy-section-title">Information Sharing</h2>
                        <div class="policy-text">
                            <p>We respect your privacy and do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                            <ul class="policy-list">
                                <li>With trusted service providers who help us operate our business</li>
                                <li>When required by law or to protect our legal rights</li>
                                <li>In case of a business transfer or merger (with prior notice)</li>
                                <li>With your explicit consent for specific purposes</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Data Security -->
                    <div class="policy-section">
                        <h2 class="policy-section-title">Data Security</h2>
                        <div class="policy-text">
                            <p>We implement industry-standard security measures to protect your personal information, including:</p>
                            <ul class="policy-list">
                                <li>Encrypted data transmission using SSL technology</li>
                                <li>Secure servers with regular security updates</li>
                                <li>Access controls limiting who can view your information</li>
                                <li>Regular security audits and monitoring</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Your Rights -->
                    <div class="policy-section">
                        <h2 class="policy-section-title">Your Rights</h2>
                        <div class="policy-text">
                            <p>You have the right to:</p>
                            <ul class="policy-list">
                                <li>Access and update your personal information</li>
                                <li>Request deletion of your account and data</li>
                                <li>Opt out of marketing communications</li>
                                <li>Request a copy of your data</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Contact Us -->
                    <div class="policy-section">
                        <h2 class="policy-section-title">Contact Us</h2>
                        <div class="policy-text">
                            <p>If you have any questions about this Privacy Policy or how we handle your personal information, please don't hesitate to contact us through our customer support channels. We're here to help and ensure your privacy concerns are addressed promptly.</p>
                        </div>
                    </div>

                    <!-- Last Updated -->
                    <div class="policy-footer">
                        <p class="policy-updated">
                            Last updated: {{ date('F j, Y') }}
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection