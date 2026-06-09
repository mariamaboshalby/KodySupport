<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function store(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $validated = $request->validate([
            'name'  => 'required|string|max:50|unique:tags,name',
            'color' => 'nullable|string|max:20',
        ]);

        $tag = Tag::create([
            'name'  => $validated['name'],
            'slug'  => Str::slug($validated['name']),
            'color' => $validated['color'] ?? '#22d3ee',
        ]);

        return response()->json([
            'id'   => $tag->id,
            'name' => $tag->name,
        ]);
    }
}
