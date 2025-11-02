@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-user-circle display-1 text-primary"></i>
                    <h2 class="mt-3 fw-bold">Welcome Back!</h2>
                    <p class="text-muted">Sign in to your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

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
                            autofocus
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
                            placeholder="Enter your password"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-3 text-muted">Or sign in with</p>
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
                    <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Sign up</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

