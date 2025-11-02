<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Cache;


class PostController extends Controller
{
    /**
     * Display a listing of posts with eager loaded relationships
     *
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        $page = request('page', 1);
        $cacheKey = "posts.index.page.{$page}";

        $posts = Cache::remember($cacheKey, 3600, function () {
            return Post::with('user')
                ->withCount('comments')
                ->orderByDesc('created_at')
                ->paginate(10);
        });

        return view('posts.index', compact('posts'));
    }

    /**
     * Display the specified post with eager loaded relationships
     *
     * @param Post $post
     * @return Factory|View
     */
    public function show(Post $post): Factory|View
    {
        $cacheKey = "post.{$post->id}.with.comments";

        if (!Cache::has($cacheKey)) {
            // Eager load user, comments, and comment users to prevent N+1 queries
            $post->load([
                'user',
                'comments' => function ($query) {
                    $query->orderByDesc('created_at');
                },
                'comments.user'
            ]);
            Cache::put($cacheKey, $post, 3600);
        } else {
            $post = Cache::get($cacheKey);
        }

        return view('posts.show', compact('post'));
    }

    /**
     * @return Factory|View
     */
    public function create(): Factory|View
    {
        return view('posts.create');
    }

    /**
     * @param PostRequest $request
     * @return RedirectResponse
     */
    public function store(PostRequest $request): RedirectResponse
    {
        $postRequest = $request->validated();
        $postRequest['user_id'] = Auth::id();
        $postRequest['published_at'] = $postRequest['published_at'] ?? now();
        $post = Post::create($postRequest);

        // Clear dashboard stats cache to reflect updated counts
        Cache::forget('admin.dashboard.stats');

        return redirect()->route('posts.my')->with('success','Post created successfully.');
    }

    /**
     * @param Post $post
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(Post $post): Factory|View
    {
        $this->authorize('update',$post);
        return view('posts.edit', compact('post'));
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update',$post);
        $post->update($request->validated());

        return redirect()->route('posts.my')->with('success','Post updated successfully.');
    }

    /**
     * Display authenticated user's posts
     *
     * @return Factory|View
     */
    public function myPosts(): Factory|View
    {
        $posts = Post::where('user_id', Auth::id())
            ->with('user')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('posts.my-posts', compact('posts'));
    }

    /**
     * @param Post $post
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function delete(Post $post): RedirectResponse
    {
        $this->authorize('delete',$post);
        $post->delete();

        // Clear dashboard stats cache to reflect updated counts
        Cache::forget('admin.dashboard.stats');

        // Check where user came from and redirect appropriately
        $referer = request()->headers->get('referer');

        // If deleting from post detail page, redirect to my-posts
        if ($referer && str_contains($referer, 'posts/' . $post->id)) {
            return redirect()->route('posts.my')->with('success','Post deleted successfully.');
        }

        // Otherwise, go back to the previous page (my-posts or all posts list)
        return redirect()->back()->with('success','Post deleted successfully.');
    }
}
