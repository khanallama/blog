<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    {{-- Hero Section --}}
    <section class="bg-primary text-white py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Welcome to My Blog</h1>
                    <p class="lead mb-4">Share your stories, thoughts, and ideas with the world. Join our community of passionate writers and readers.</p>
                    <div class="d-flex gap-3">
                        @auth
                            <a href="{{ route('posts.index') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-home"></i> Go to Blog
                            </a>
                            <a href="{{ route('posts.create') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-plus-circle"></i> Create Post
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-user-plus"></i> Get Started
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Sign In
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <i class="fas fa-book display-1"></i>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Why Choose Our Blog Platform?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-pen display-4 text-primary mb-3"></i>
                            <h4 class="fw-bold">Easy to Write</h4>
                            <p class="text-muted">Create beautiful blog posts with our simple and intuitive editor.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users display-4 text-success mb-3"></i>
                            <h4 class="fw-bold">Growing Community</h4>
                            <p class="text-muted">Connect with readers and writers from around the world.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-shield-alt display-4 text-info mb-3"></i>
                            <h4 class="fw-bold">Secure & Reliable</h4>
                            <p class="text-muted">Your content is safe with our modern security measures.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-5 bg-dark text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Ready to Start Writing?</h2>
            <p class="lead mb-4">Join thousands of writers sharing their stories today.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-rocket"></i> Get Started for Free
                </a>
            @else
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle"></i> Create Your First Post
                </a>
            @endguest
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-light py-4">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} My Blog. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

