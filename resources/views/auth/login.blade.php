@extends('layout.app')

@section('title', 'Login - Tek Souq')

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
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your account to continue</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
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
                           placeholder="Enter your email">
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" 
                           type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="Enter your password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       name="remember" 
                       id="remember" 
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember me for 30 days
                </label>
            </div>
            
            <button type="submit" class="btn-auth btn-auth-primary">
                Sign In
            </button>
        </form>

        @if (Route::has('password.request'))
            <div class="forgot-password">
                <a href="{{ route('password.request') }}" class="auth-link">
                    Forgot your password?
                </a>
            </div>
        @endif
        
        <div class="auth-divider">
            <span>Don't have an account?</span>
        </div>
        
        <div class="auth-links">
            <a href="{{ route('register') }}" class="auth-link">
                Create a new account
            </a>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Add loading state to form submission
document.querySelector('.auth-form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.classList.add('btn-loading');
    submitBtn.textContent = 'Signing In...';
});
</script>
@endsection