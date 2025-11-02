@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-5 text-center">
                    <i class="fas fa-tachometer-alt display-1 text-primary mb-4"></i>
                    <h1 class="display-5 fw-bold mb-3">Dashboard</h1>
                    <p class="lead text-muted mb-4">Welcome back, {{ auth()->user()->name }}! You're successfully logged
                        in.</p>

                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        @role('user')
                        <a href="{{ route('posts.index') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Go to Blog
                        </a>
                        <a href="{{ route('posts.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Create Post
                        </a>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

