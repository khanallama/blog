@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}'s Posts</li>
            </ol>
        </nav>
    </div>
</div>

{{-- User Info Card --}}
<div class="card mb-4 shadow-sm border-primary">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <h3 class="mb-0">{{ strtoupper(substr($user->name, 0, 1)) }}</h3>
                    </div>
                    <div>
                        <h2 class="mb-1">{{ $user->name }}</h2>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        @foreach($user->roles as $role)
                            <span class="badge {{ $role->name === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <p class="mb-0 text-muted">Member since</p>
                <h5 class="mb-0">{{ $user->created_at->format('M d, Y') }}</h5>
            </div>
        </div>
    </div>
</div>

{{-- Posts Table --}}
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="fas fa-file-alt"></i> All Posts ({{ $posts->total() }})</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Comments</th>
                        <th>Published</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr class="{{ $post->trashed() ? 'table-danger' : '' }}">
                            <td>#{{ ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none" target="_blank">
                                    <strong>{{ Str::limit($post->title, 50) }}</strong>
                                </a>
                                <br>
                                <small class="text-muted">{{ Str::limit($post->body, 80) }}</small>
                            </td>
                            <td>
                                @if($post->trashed())
                                    <span class="badge bg-danger">Deleted</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $post->comments_count }}</span>
                            </td>
                            <td>
                                <small>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Not published' }}</small>
                            </td>
                            <td>
                                <small>{{ $post->created_at->format('M d, Y') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary" target="_blank" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($post->trashed())
                                        <form action="{{ route('admin.posts.restore', $post->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Restore">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.posts.delete', $post) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Delete this post?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-inbox display-3"></i>
                                <p class="mt-2">{{ $user->name }} hasn't created any posts yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($posts->hasPages())
        <div class="card-footer bg-white">
            {{ $posts->links() }}
        </div>
    @endif
</div>

<div class="mt-4">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>
@endsection

