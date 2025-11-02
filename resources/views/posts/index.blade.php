@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-5 fw-bold"><i class="fas fa-newspaper"></i> Blog Posts</h1>
            @auth
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Create New Post
                </a>
            @endauth
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                            {{ $post->title }}
                        </a>
                    </h5>
                    <p class="card-text text-muted">{{ Str::limit($post->body, 150) }}</p>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-user"></i> 
                            @auth
                                @if($post->user_id === auth()->id())
                                    <span class="badge bg-primary">You</span>
                                @else
                                    {{ $post->user ? $post->user->name : 'Unknown' }}
                                @endif
                            @else
                                {{ $post->user ? $post->user->name : 'Unknown' }}
                            @endauth
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> {{ $post->created_at->format('M d, Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                <i class="fas fa-info-circle"></i> No posts available yet.
                @auth
                    <a href="{{ route('posts.create') }}" class="alert-link">Create the first post!</a>
                @endauth
            </div>
        </div>
    @endforelse
</div>

@if($posts->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            {{ $posts->links() }}
        </div>
    </div>
@endif
@endsection

