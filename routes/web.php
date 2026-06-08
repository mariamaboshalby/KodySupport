<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminTaxonomyController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', [PostController::class, 'index'])->name('home');
Route::permanentRedirect('/dashboard', '/')->name('dashboard');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/c/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// ── Authenticated ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Posts CRUD
    Route::get('/submit', [PostController::class, 'create'])->name('posts.create');
    Route::post('/submit', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post:slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post:slug}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post:slug}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Comments
    Route::post('/posts/{post:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Votes (JSON API)
    Route::post('/votes/posts/{post}', [VoteController::class, 'votePost'])->name('votes.post');
    Route::post('/votes/comments/{comment}', [VoteController::class, 'voteComment'])->name('votes.comment');

    // Bookmarks
    Route::post('/bookmarks/{post}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categories (admin only)
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    // Tags (admin only)
    Route::post('/tags', [\App\Http\Controllers\TagController::class, 'store'])->name('tags.store');

    // Post Types (admin only)
    Route::post('/post-types', [\App\Http\Controllers\PostTypeController::class, 'store'])->name('post_types.store');

    // Image upload for post editor
    Route::post('/upload/image', function (Request $request) {
        $request->validate(['image' => 'required|image|max:4096']);
        $path = $request->file('image')->store('post-images', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
    })->name('upload.image');
});

// ── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Posts
    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::patch('/posts/{post}/pin', [AdminPostController::class, 'togglePin'])->name('posts.pin');
    Route::patch('/posts/{post}/lock', [AdminPostController::class, 'toggleLock'])->name('posts.lock');
    Route::patch('/posts/{post}/status', [AdminPostController::class, 'updateStatus'])->name('posts.status');
    Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
    Route::patch('/posts/{id}/restore', [AdminPostController::class, 'restore'])->name('posts.restore');
    Route::delete('/posts/{id}/force', [AdminPostController::class, 'forceDelete'])->name('posts.force-delete');

    // Comments
    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/comments/{id}/restore', [AdminCommentController::class, 'restore'])->name('comments.restore');
    Route::delete('/comments/{id}/force', [AdminCommentController::class, 'forceDelete'])->name('comments.force-delete');

    // Categories
    Route::get('/categories', [AdminTaxonomyController::class, 'categoriesIndex'])->name('categories.index');
    Route::post('/categories', [AdminTaxonomyController::class, 'categoryStore'])->name('categories.store');
    Route::patch('/categories/{category}', [AdminTaxonomyController::class, 'categoryUpdate'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminTaxonomyController::class, 'categoryDestroy'])->name('categories.destroy');

    // Tags
    Route::get('/tags', [AdminTaxonomyController::class, 'tagsIndex'])->name('tags.index');
    Route::post('/tags', [AdminTaxonomyController::class, 'tagStore'])->name('tags.store');
    Route::patch('/tags/{tag}', [AdminTaxonomyController::class, 'tagUpdate'])->name('tags.update');
    Route::delete('/tags/{tag}', [AdminTaxonomyController::class, 'tagDestroy'])->name('tags.destroy');

    // Post Types
    Route::get('/post-types', [AdminTaxonomyController::class, 'postTypesIndex'])->name('post-types.index');
    Route::post('/post-types', [AdminTaxonomyController::class, 'postTypeStore'])->name('post-types.store');
    Route::patch('/post-types/{postType}', [AdminTaxonomyController::class, 'postTypeUpdate'])->name('post-types.update');
    Route::delete('/post-types/{postType}', [AdminTaxonomyController::class, 'postTypeDestroy'])->name('post-types.destroy');
});

// ── Breeze Auth Routes ────────────────────────────────────────────────────────
require __DIR__ . '/auth.php';
