<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        abort_unless($post->status === 'published', 404);
        abort_if($post->is_locked, 403, 'This thread is locked.');

        $validated = $request->validate([
            'body'      => 'required|string|min:2|max:10000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'post_id'   => $post->id,
            'user_id'   => auth()->id(),
            'body'      => $validated['body'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Update denormalized counter
        $post->increment('comments_count');

        if ($request->expectsJson()) {
            return response()->json([
                'comment' => $comment->load('author'),
                'message' => 'Comment posted.',
            ]);
        }

        return back()->with('success', 'Comment posted.')->withFragment('comment-' . $comment->id);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->post->decrement('comments_count');
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
