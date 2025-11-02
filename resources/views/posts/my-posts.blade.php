@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-5 fw-bold"><i class="fas fa-user-edit"></i> My Posts</h1>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Create New Post
            </a>
        </div>
    </div>
</div>


<div class="row mb-4 d-flex justify-content-center">
    <div class="col-md-3">
        <div class="card border-primary shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-file-alt text-primary display-4"></i>
                <h3 class="mt-2 mb-0">{{ $posts->total() }}</h3>
                <p class="text-muted mb-0">Total Posts</p>
            </div>
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
                            <i class="fas fa-comments"></i> {{ $post->comments_count }} comments
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> {{ $post->created_at->format('M d, Y') }}
                        </small>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('posts.delete', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this post?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                <i class="fas fa-info-circle"></i> You haven't created any posts yet.
                <a href="{{ route('posts.create') }}" class="alert-link">Create your first post!</a>
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


