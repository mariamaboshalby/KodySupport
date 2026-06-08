<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PostType;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminTaxonomyController extends Controller
{
    // ── Categories ────────────────────────────────────────────────────────────

    public function categoriesIndex()
    {
        $categories = Category::withCount('posts')->orderBy('sort_order')->get();
        return view('admin.taxonomy.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:50',
        ]);

        Category::create([
            'name'       => $validated['name'],
            'slug'       => Str::slug($validated['name']),
            'description'=> $validated['description'] ?? null,
            'color'      => $validated['color'] ?? '#22d3ee',
            'icon'       => $validated['icon'] ?? null,
            'sort_order' => Category::max('sort_order') + 1,
            'is_active'  => true,
        ]);

        return back()->with('success', 'تم إنشاء التصنيف.');
    }

    public function categoryUpdate(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:50',
            'is_active'   => 'boolean',
        ]);

        $category->update($validated);

        return back()->with('success', 'تم تحديث التصنيف.');
    }

    public function categoryDestroy(Category $category)
    {
        // Move posts to no category
        $category->posts()->update(['category_id' => null]);
        $category->delete();
        return back()->with('success', 'تم حذف التصنيف.');
    }

    // ── Tags ──────────────────────────────────────────────────────────────────

    public function tagsIndex()
    {
        $tags = Tag::withCount('posts')->orderBy('name')->get();
        return view('admin.taxonomy.tags', compact('tags'));
    }

    public function tagStore(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50|unique:tags,name',
            'color' => 'nullable|string|max:20',
        ]);

        Tag::create([
            'name'  => $validated['name'],
            'slug'  => Str::slug($validated['name']),
            'color' => $validated['color'] ?? '#22d3ee',
        ]);

        return back()->with('success', 'تم إنشاء الوسم.');
    }

    public function tagUpdate(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50|unique:tags,name,' . $tag->id,
            'color' => 'nullable|string|max:20',
        ]);

        $tag->update($validated);

        return back()->with('success', 'تم تحديث الوسم.');
    }

    public function tagDestroy(Tag $tag)
    {
        $tag->posts()->detach();
        $tag->delete();
        return back()->with('success', 'تم حذف الوسم.');
    }

    // ── Post Types ────────────────────────────────────────────────────────────

    public function postTypesIndex()
    {
        $postTypes = PostType::ordered()->get();
        return view('admin.taxonomy.post-types', compact('postTypes'));
    }

    public function postTypeStore(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        $slug = Str::slug($validated['name']);

        if (PostType::where('slug', $slug)->exists()) {
            return back()->withErrors(['name' => 'هذا النوع موجود مسبقاً.']);
        }

        PostType::create([
            'name'       => $validated['name'],
            'slug'       => $slug,
            'color'      => $validated['color'] ?? '#22d3ee',
            'sort_order' => PostType::max('sort_order') + 1,
        ]);

        return back()->with('success', 'تم إنشاء نوع المقال.');
    }

    public function postTypeUpdate(Request $request, PostType $postType)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        $postType->update($validated);

        return back()->with('success', 'تم تحديث نوع المقال.');
    }

    public function postTypeDestroy(PostType $postType)
    {
        abort_if($postType->is_default, 403, 'لا يمكن حذف النوع الافتراضي.');
        $postType->delete();
        return back()->with('success', 'تم حذف نوع المقال.');
    }
}
