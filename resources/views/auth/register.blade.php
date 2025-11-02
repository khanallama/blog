@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-user-plus display-1 text-success"></i>
                    <h2 class="mt-3 fw-bold">Create Account</h2>
                    <p class="text-muted">Join our blogging community</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">
                            <i class="fas fa-user"></i> Full Name
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            placeholder="Enter your full name"
                            required 
                            autofocus
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="password" 
                            name="password" 
                            placeholder="Create a password"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Must be at least 8 characters long</div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold">
                            <i class="fas fa-lock"></i> Confirm Password
                        </label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="Confirm your password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-success w-100 mb-3">
                        <i class="fas fa-check-circle"></i> Create Account
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-3 text-muted">Or sign up with</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('auth.google') }}" class="btn btn-outline-danger">
                            <i class="fab fa-google"></i> Continue with Google
                        </a>
                        <a href="{{ route('auth.facebook') }}" class="btn btn-outline-primary">
                            <i class="fab fa-facebook"></i> Continue with Facebook
                        </a>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

