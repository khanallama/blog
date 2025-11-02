<?php

use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Services\DashboardStatsService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GUEST ROUTES (Public/Unauthenticated)
|--------------------------------------------------------------------------
*/

Route::get('/', [PostController::class, 'index'])->name('posts.index');

/*
|--------------------------------------------------------------------------
| USER ROUTES (Authenticated Regular Users)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth.user', 'log.activity'])->group(function () {
    Route::get('my-posts', [PostController::class, 'myPosts'])->name('posts.my');

    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'delete'])->name('posts.delete');

    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'delete'])->name('comments.delete');
});

Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Authenticated Admins Only)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin', 'log.activity'])->group(function () {

    Route::get('dashboard', function (DashboardStatsService $service) {
        $stats = $service->stats();
        $users = $service->getUsers();
        return view('admin.dashboard', compact('stats', 'users'));
    })->name('dashboard');

    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'delete'])->name('users.delete');
    Route::get('users/{user}/posts', [AdminUserController::class, 'posts'])->name('users.posts');

    Route::delete('posts/{post}', [AdminPostController::class, 'delete'])->name('posts.delete');
    Route::post('posts/{id}/restore', [AdminPostController::class, 'restore'])->name('posts.restore');
});

require __DIR__ . '/auth.php';
