<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Cache;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $this->clearCache($comment);
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        $this->clearCache($comment);
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        $this->clearCache($comment);
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        $this->clearCache($comment);
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        $this->clearCache($comment);
    }

    /**
     * Clear all relevant caches when a comment changes.
     */
    protected function clearCache(Comment $comment): void
    {
        // Clear the post cache that contains this comment
        Cache::forget("post.{$comment->post_id}.with.comments");

        // Clear admin dashboard stats
        Cache::forget('admin.dashboard.stats');
    }
}

