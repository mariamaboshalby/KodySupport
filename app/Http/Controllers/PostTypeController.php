<?php

namespace App\Http\Controllers;

use App\Models\PostType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostTypeController extends Controller
{
    public function store(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        $slug = Str::slug($validated['name']);

        if (PostType::where('slug', $slug)->exists()) {
            return response()->json(['error' => 'هذا النوع موجود مسبقاً.'], 422);
        }

        $maxOrder = PostType::max('sort_order') ?? 0;

        $type = PostType::create([
            'name'       => $validated['name'],
            'slug'       => $slug,
            'color'      => $validated['color'] ?? '#22d3ee',
            'sort_order' => $maxOrder + 1,
        ]);

        return response()->json([
            'id'    => $type->id,
            'slug'  => $type->slug,
            'name'  => $type->name,
            'color' => $type->color,
        ]);
    }
}
