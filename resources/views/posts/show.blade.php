@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h1 class="card-title display-6 fw-bold mb-3">{{ $post->title }}</h1>

                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <span class="badge bg-secondary">
                            <i class="fas fa-user"></i> 
                            @auth
                                @if($post->user_id === auth()->id())
                                    You
                                @else
                                    {{ $post->user ? $post->user->name : 'Unknown' }}
                                @endif
                            @else
                                {{ $post->user ? $post->user->name : 'Unknown' }}
                            @endauth
                        </span>
                        <span class="badge bg-info text-dark ms-2">
                            <i class="fas fa-calendar"></i> {{ $post->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    @can('update', $post)
                        <div>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    @endcan
                </div>

                <div class="post-content">
                    <p class="lead">{{ $post->body }}</p>
                </div>

                @can('delete', $post)
                    <div class="mt-4 pt-3 border-top">
                        <form action="{{ route('posts.delete', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">
                                <i class="fas fa-trash"></i> Delete Post
                            </button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="fas fa-comments"></i> Comments ({{ $post->comments->count() }})</h4>
            </div>
            <div class="card-body">
                @auth
                    <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="body" class="form-label fw-bold">Add a Comment</label>
                            <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="3" placeholder="Write your comment here..." required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Post Comment
                        </button>
                    </form>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Please <a href="{{ route('login') }}" class="alert-link">login</a> to leave a comment.
                    </div>
                @endauth

                <hr>

                @forelse($post->comments as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-user-circle text-primary"></i> 
                                        @auth
                                            @if($comment->user_id === auth()->id())
                                                You
                                            @else
                                                {{ $comment->user ? $comment->user->name : 'Unknown' }}
                                            @endif
                                        @else
                                            {{ $comment->user ? $comment->user->name : 'Unknown' }}
                                        @endauth
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @auth
                                    @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                                        <div class="btn-group btn-group-sm">
                                            @can('update', $comment)
                                                <a href="{{ route('comments.edit', $comment) }}" class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $comment)
                                                <form action="{{ route('comments.delete', $comment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this comment?')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    @endif
                                @endauth
                            </div>
                            <p class="mt-2 mb-0">{{ $comment->body }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-quote-left display-4"></i>
                        <p class="mt-2">No comments yet. Be the first to comment!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Posts
            </a>
        </div>
    </div>
</div>
@endsection

