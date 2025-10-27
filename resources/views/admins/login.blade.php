@extends('layouts.admin')

@section('title', 'Login')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header text-center">
            <img src="{{ asset('assets/images/image_5.jpg') }}" alt="9NineCoffee Logo" class="login-logo">
            <h3 class="fw-bold text-dark">9NineCoffee</h3>
            <p class="text-muted mb-4">Sign in to your account</p>
        </div>

        {{-- Display session error --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Admin login form --}}
        <form action="{{ route('check.login') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Enter your password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label for="remember" class="form-check-label">Remember Me</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </button>
        </form>

        {{-- Register link --}}
        <div class="text-center mt-4">
            <small class="text-muted">Don’t have an account?</small>
            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">Register</a>
        </div>

        {{-- Login as customer link --}}
        <div class="text-center mt-2">
            <small class="text-muted">Or login as a customer:</small>
            <a href="{{ route('login') }}" class="btn btn-outline-success w-100 mt-1">
                <i class="bi bi-person-circle me-1"></i> Customer Login
            </a>
        </div>
    </div>
</div>
@endsection
