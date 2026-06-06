<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Post;

class BookmarkController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();

        $bookmark = Bookmark::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
        } else {
            Bookmark::create(['user_id' => $user->id, 'post_id' => $post->id]);
            $bookmarked = true;
        }

        if (request()->expectsJson()) {
            return response()->json(['bookmarked' => $bookmarked]);
        }

        return back()->with('success', $bookmarked ? 'Saved to bookmarks.' : 'Removed from bookmarks.');
    }

    public function index()
    {
        $posts = auth()->user()
            ->bookmarks()
            ->with('post.author', 'post.category', 'post.tags')
            ->latest()
            ->paginate(15);

        return view('bookmarks.index', compact('posts'));
    }
}
