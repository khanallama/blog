<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    /**
     * Store a newly created comment
     * 
     * @param CommentRequest $request
     * @param Post $post
     * @return RedirectResponse
     */
    public function store(CommentRequest $request, Post $post): RedirectResponse
    {
        $commentRequest = $request->validated();
        $commentRequest['user_id'] = auth()->id();
        $post->comments()->create($commentRequest);

        // Clear dashboard stats cache to reflect updated counts
        Cache::forget('admin.dashboard.stats');

        return redirect()->back()->with('success','Comment added successfully.');
    }

    /**
     * Show the form for editing a comment
     * 
     * @param Comment $comment
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(Comment $comment): Factory|View
    {
        $this->authorize('update', $comment);
        
        // Load the post relationship
        $comment->load('post');
        
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment
     * 
     * @param CommentRequest $request
     * @param Comment $comment
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(CommentRequest $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);
        
        $comment->update($request->validated());

        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', 'Comment updated successfully.');
    }

    /**
     * Delete the specified comment
     * 
     * @param Comment $comment
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function delete(Comment $comment): RedirectResponse
    {
        $this->authorize('delete',$comment);
        $comment->delete();

        // Clear dashboard stats cache to reflect updated counts
        Cache::forget('admin.dashboard.stats');

        return redirect()->back()->with('success','Comment deleted successfully.');
    }
}
