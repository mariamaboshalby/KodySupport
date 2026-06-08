<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['author', 'category', 'tags'])
            ->published()
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at');

        // Filter by type
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->forCategory($category->id);
            }
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $tag = Tag::where('slug', $request->tag)->first();
            if ($tag) {
                $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id));
            }
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'top'     => $query->reorder()->orderByDesc('votes_count'),
            'hot'     => $query->reorder()->orderByDesc('comments_count'),
            default   => null,
        };

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($s) =>
                $s->where('title', 'like', "%{$q}%")
                  ->orWhere('body', 'like', "%{$q}%")
            );
        }

        $posts      = $query->paginate(15)->withQueryString();
        $categories = Category::active()->roots()->withCount(['posts' => fn ($q) => $q->published()])->orderBy('sort_order')->get();
        $pinned     = Post::published()->pinned()->latest('published_at')->take(3)->get();
        $popular    = Post::published()->orderByDesc('views_count')->take(5)->get();

        return view('posts.index', compact('posts', 'categories', 'pinned', 'popular'));
    }

    public function show(Post $post)
    {
        abort_unless($post->status === 'published', 404);

        // Increment views
        $post->increment('views_count');

        $post->load([
            'author', 'category', 'tags',
            'comments.author', 'comments.replies.author',
        ]);

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where(fn ($q) =>
                $q->where('category_id', $post->category_id)
                  ->orWhereHas('tags', fn ($t) =>
                      $t->whereIn('tags.id', $post->tags->pluck('id'))
                  )
            )
            ->take(4)
            ->get();

        return view('posts.show', compact('post', 'related'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);
        $categories = Category::active()->roots()->get();
        $tags       = Tag::orderBy('name')->get();
        $postTypes  = \App\Models\PostType::ordered()->get();
        return view('posts.create', compact('categories', 'tags', 'postTypes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string|min:10',
            'excerpt'     => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'type'        => 'required|string|exists:post_types,slug',
            'status'      => 'required|in:draft,published',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'cover_image' => 'nullable|image|max:4096',
            'is_pinned'   => 'boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $validated['user_id']      = auth()->id();
        $validated['slug']         = Str::slug($validated['title']);
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;

        $post = Post::create($validated);

        if (! empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post published successfully!');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $categories = Category::active()->roots()->get();
        $tags       = Tag::orderBy('name')->get();
        $postTypes  = \App\Models\PostType::ordered()->get();
        return view('posts.edit', compact('post', 'categories', 'tags', 'postTypes'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string|min:10',
            'excerpt'     => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'type'        => 'required|string|exists:post_types,slug',
            'status'      => 'required|in:draft,published',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'cover_image' => 'nullable|image|max:4096',
            'is_pinned'   => 'boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old cover if exists
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        } elseif ($request->boolean('remove_cover_image') && $post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
            $validated['cover_image'] = null;
        }

        if ($validated['status'] === 'published' && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);
        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted.');
    }
}
