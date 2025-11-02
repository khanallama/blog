@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold"><i class="fas fa-shield-alt text-danger"></i> Admin Dashboard</h1>
        <p class="text-muted">Manage your blog, users, and content.</p>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card border-primary shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-file-alt display-1 text-primary"></i>
                <h3 class="mt-3 fw-bold">{{ $stats['posts'] }}</h3>
                <p class="text-muted mb-0">Total Posts</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-success shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-users display-1 text-success"></i>
                <h3 class="mt-3 fw-bold">{{ $stats['users'] }}</h3>
                <p class="text-muted mb-3">Total Users</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-success">
                    Manage Users <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Users and Their Posts Table --}}
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="fas fa-users"></i> Users & Their Posts</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-user"></i> User</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-shield-alt"></i> Role</th>
                        <th><i class="fas fa-file-alt"></i> Posts</th>
                        <th><i class="bi bi-chat"></i> Comments</th>
                        <th><i class="fas fa-calendar"></i> Joined</th>
                        <th class="text-center"><i class="fas fa-cog"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        <strong>{{ strtoupper(substr($user->name, 0, 1)) }}</strong>
                                    </div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge {{ $role->name === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.users.posts', $user) }}" class="fw-bold text-decoration-none">
                                    <i class="fas fa-file-alt text-primary"></i> {{ $user->posts_count }} posts
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $user->comments_count }}</span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary" title="Edit Role">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete User" onclick="return confirm('Delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white">
            {{ $users->links() }}
        </div>
    @endif
    </div>
@endsection

