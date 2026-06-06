<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

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
}
