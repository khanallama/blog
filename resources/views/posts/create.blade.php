@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-plus-circle"></i> Create New Post</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('posts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Post Title <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('title') is-invalid @enderror" 
                            id="title" 
                            name="title" 
                            value="{{ old('title') }}" 
                            placeholder="Enter an engaging title..."
                            required
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label fw-bold">Content <span class="text-danger">*</span></label>
                        <textarea 
                            class="form-control @error('body') is-invalid @enderror" 
                            id="body" 
                            name="body" 
                            rows="10" 
                            placeholder="Write your post content here..."
                            required
                        >{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Share your thoughts, stories, or insights with the community.</div>
                    </div>

                    <div class="mb-4">
                        <label for="published_at" class="form-label fw-bold">Publish Date (Optional)</label>
                        <input 
                            type="datetime-local" 
                            class="form-control @error('published_at') is-invalid @enderror" 
                            id="published_at" 
                            name="published_at" 
                            value="{{ old('published_at') }}"
                        >
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave empty to publish immediately.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle"></i> Create Post
                        </button>
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

