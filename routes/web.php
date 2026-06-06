<?php

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

    // Image upload for post editor
    Route::post('/upload/image', function (Request $request) {
        $request->validate(['image' => 'required|image|max:4096']);
        $path = $request->file('image')->store('post-images', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
    })->name('upload.image');
});

// ── Breeze Auth Routes ────────────────────────────────────────────────────────
require __DIR__ . '/auth.php';
