<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    /**
     * Get dashboard statistics
     *
     * @return array
     */
    public function stats(): array
    {
        return Cache::remember('admin.dashboard.stats', 300, function () {
            return [
                'posts' => Post::count(),
                'users' => User::role('user')->count(),
                'comments' => Comment::count(),
            ];
        });
    }

    /**
     * Get paginated users with their posts and comments count
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUsers(int $perPage = 10): LengthAwarePaginator
    {
        return User::role('user')
            ->withCount(['posts', 'comments'])
            ->with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
