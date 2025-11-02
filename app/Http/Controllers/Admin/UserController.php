<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users with eager loaded relationships
     *
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        $users = User::role('user')
            ->with('roles')
            ->withCount(['posts', 'comments'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * @param User $user
     * @return Factory|View
     */
    public function edit(User $user): Factory|View
    {
        $roles = Role::pluck('name');
        return view('admin.users.edit', compact('user','roles'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role'=>'required|string']);
        $user->syncRoles([$request->role]);

        // Clear dashboard stats cache to reflect updated counts
        Cache::forget('admin.dashboard.stats');

        return redirect()->route('admin.users.index')->with('success','Role updated.');
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function delete(User $user): RedirectResponse
    {
        $user->delete();

        // Clear dashboard stats cache to reflect updated counts
        Cache::forget('admin.dashboard.stats');

        return back()->with('success','User deleted.');
    }

    /**
     * Display user's posts with eager loaded relationships
     *
     * @param User $user
     * @return Factory|View
     */
    public function posts(User $user): Factory|View
    {
        // Eager load user to prevent N+1 query
        $user->load('roles');

        $posts = $user->posts()
            ->withTrashed()
            ->with('user')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.users.posts', compact('user', 'posts'));
    }
}
