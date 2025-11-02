<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $this->clearCache($post);
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $this->clearCache($post);
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $this->clearCache($post);
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        $this->clearCache($post);
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        $this->clearCache($post);
    }

    /**
     * Clear all relevant caches when a post changes.
     */
    protected function clearCache(Post $post): void
    {
        // Clear posts index cache (all pages)
        for ($page = 1; $page <= 100; $page++) {
            Cache::forget("posts.index.page.{$page}");
        }

        // Clear admin posts index cache (all pages)
        for ($page = 1; $page <= 100; $page++) {
            Cache::forget("admin.posts.index.page.{$page}");
        }

        // Clear specific post cache
        Cache::forget("post.{$post->id}.with.comments");

        // Clear admin dashboard stats
        Cache::forget('admin.dashboard.stats');
    }
}

