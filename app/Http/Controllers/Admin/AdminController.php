<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostType;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users'         => User::count(),
            'posts'         => Post::withTrashed()->count(),
            'published'     => Post::where('status', 'published')->count(),
            'drafts'        => Post::where('status', 'draft')->count(),
            'comments'      => Comment::withTrashed()->count(),
            'votes'         => Vote::count(),
            'bookmarks'     => Bookmark::count(),
            'categories'    => Category::count(),
            'tags'          => Tag::count(),
            'post_types'    => PostType::count(),
        ];

        $recentPosts = Post::with(['author', 'category'])
            ->withTrashed()
            ->latest()
            ->take(8)
            ->get();

        $recentUsers = User::latest()->take(6)->get();

        $recentComments = Comment::with(['author', 'post'])
            ->withTrashed()
            ->latest()
            ->take(6)
            ->get();

        $topPosts = Post::published()
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $newPostsThisMonth = Post::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.dashboard', compact(
            'stats', 'recentPosts', 'recentUsers',
            'recentComments', 'topPosts',
            'newUsersThisMonth', 'newPostsThisMonth'
        ));
    }
}
