@extends('layout.app')

@section('title', 'Reset Password - Tek Souq')

@push('styles')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-overlay"></div>
    
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('home') }}" class="auth-logo">
                <img src="{{ asset('images/logo.png') }}" alt="TekSouq Logo" class="auth-logo-img">
            </a>
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Enter your email address and we'll send you a link to reset your password</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" 
                           type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email" 
                           autofocus
                           placeholder="Enter your email address">
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn-auth btn-auth-primary">
                Send Password Reset Link
            </button>
        </form>
        
        <div class="auth-divider">
            <span>Remember your password?</span>
        </div>
        
        <div class="auth-links">
            <a href="{{ route('login') }}" class="auth-link">
                Back to Sign In
            </a>
        </div>
        
        <div class="auth-links" style="margin-top: 1rem;">
            <a href="{{ route('register') }}" class="auth-link">
                Create a new account
            </a>
        </div>
    </div>
</div>

<script>
// Add loading state to form submission
document.querySelector('.auth-form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.classList.add('btn-loading');
    submitBtn.textContent = 'Sending...';
});
</script>
@endsection