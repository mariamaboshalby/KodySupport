<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['author', 'post'])->withTrashed();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('body', 'like', "%{$q}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'trashed') {
                $query->onlyTrashed();
            } else {
                $query->withoutTrashed();
            }
        }

        $comments = $query->latest()->paginate(25)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function destroy(Comment $comment)
    {
        $comment->post?->decrement('comments_count');
        $comment->delete();
        return back()->with('success', 'تم حذف التعليق.');
    }

    public function restore($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $comment->restore();
        $comment->post?->increment('comments_count');
        return back()->with('success', 'تم استعادة التعليق.');
    }

    public function forceDelete($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $comment->forceDelete();
        return back()->with('success', 'تم حذف التعليق نهائياً.');
    }
}
