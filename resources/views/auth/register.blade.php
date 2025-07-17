@extends('layout.app')

@section('title', 'Register - Tek Souq')

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
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join TekSouq and discover amazing tech products</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input id="name" 
                           type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autocomplete="name" 
                           autofocus
                           placeholder="Enter your full name">
                </div>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
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
                           autocomplete="new-password"
                           placeholder="Create a strong password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password-confirm" 
                           type="password" 
                           class="form-control" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password"
                           placeholder="Confirm your password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       name="terms" 
                       id="terms" 
                       required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="#" class="auth-link">Terms of Service</a> and <a href="#" class="auth-link">Privacy Policy</a>
                </label>
            </div>
            
            <button type="submit" class="btn-auth btn-auth-primary">
                Create Account
            </button>
        </form>
        
        <div class="auth-divider">
            <span>Already have an account?</span>
        </div>
        
        <div class="auth-links">
            <a href="{{ route('login') }}" class="auth-link">
                Sign in to your account
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

// Password strength indicator
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strength = calculatePasswordStrength(password);
    updatePasswordStrength(strength);
});

function calculatePasswordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    return score;
}

function updatePasswordStrength(strength) {
    // Add visual password strength indicator if needed
}

// Add loading state to form submission
document.querySelector('.auth-form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.classList.add('btn-loading');
    submitBtn.textContent = 'Creating Account...';
});
</script>
@endsection