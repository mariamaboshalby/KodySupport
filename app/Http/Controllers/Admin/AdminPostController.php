<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['author', 'category'])->withTrashed();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($s) =>
                $s->where('title', 'like', "%{$q}%")
                  ->orWhere('body', 'like', "%{$q}%")
            );
        }

        if ($request->filled('status')) {
            if ($request->status === 'trashed') {
                $query->onlyTrashed();
            } else {
                $query->withoutTrashed()->where('status', $request->status);
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $posts = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function togglePin(Post $post)
    {
        $post->update(['is_pinned' => ! $post->is_pinned]);
        $label = $post->is_pinned ? 'تم تثبيت المقال.' : 'تم إلغاء تثبيت المقال.';
        return back()->with('success', $label);
    }

    public function toggleLock(Post $post)
    {
        $post->update(['is_locked' => ! $post->is_locked]);
        $label = $post->is_locked ? 'تم قفل المقال.' : 'تم فتح المقال.';
        return back()->with('success', $label);
    }

    public function updateStatus(Request $request, Post $post)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,published,archived',
        ]);

        $data = ['status' => $validated['status']];
        if ($validated['status'] === 'published' && ! $post->published_at) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return back()->with('success', 'تم تحديث حالة المقال.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'تم نقل المقال إلى سلة المحذوفات.');
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();
        return back()->with('success', 'تم استعادة المقال.');
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->forceDelete();
        return back()->with('success', 'تم حذف المقال نهائياً.');
    }
}
