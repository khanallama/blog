@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Comment</h4>
            </div>
            <div class="card-body">
                {{-- Post Info --}}
                <div class="alert alert-info mb-4">
                    <strong>On Post:</strong> 
                    <a href="{{ route('posts.show', $comment->post) }}" class="alert-link">
                        {{ $comment->post->title }}
                    </a>
                </div>

                {{-- Edit Form --}}
                <form action="{{ route('comments.update', $comment) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="body" class="form-label">Comment <span class="text-danger">*</span></label>
                        <textarea 
                            class="form-control @error('body') is-invalid @enderror" 
                            id="body" 
                            name="body" 
                            rows="5" 
                            required
                            placeholder="Write your comment here..."
                        >{{ old('body', $comment->body) }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum 1000 characters</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Comment
                        </button>
                        <a href="{{ route('posts.show', $comment->post) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

