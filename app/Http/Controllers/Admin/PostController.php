<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * @param Post $post
     * @return RedirectResponse
     */
    public function delete(Post $post): RedirectResponse
    {
        $post->forceDelete();

        // Clear dashboard stats cache to reflect updated counts
        Cache::forget('admin.dashboard.stats');

        return back()->with('success','Post permanently deleted.');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function restore($id): RedirectResponse
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        // Clear dashboard stats cache to reflect updated counts
        Cache::forget('admin.dashboard.stats');

        return back()->with('success','Post restored.');
    }
}
