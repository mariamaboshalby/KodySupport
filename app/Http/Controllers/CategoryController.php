<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $posts = Post::with(['author', 'category', 'tags'])
            ->published()
            ->forCategory($category->id)
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->paginate(15);

        $categories = Category::active()->roots()
            ->withCount(['posts' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        return view('categories.show', compact('category', 'posts', 'categories'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $validated = $request->validate([
            'name'  => 'required|string|max:100|unique:categories,name',
            'color' => 'nullable|string|max:20',
        ]);

        $category = Category::create([
            'name'       => $validated['name'],
            'slug'       => Str::slug($validated['name']),
            'color'      => $validated['color'] ?? '#22d3ee',
            'sort_order' => Category::max('sort_order') + 1,
            'is_active'  => true,
        ]);

        return response()->json([
            'id'   => $category->id,
            'name' => $category->name,
        ]);
    }
}
