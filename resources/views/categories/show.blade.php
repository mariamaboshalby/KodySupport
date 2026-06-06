@extends('layouts.app')
@section('title', $category->name)
@section('description', $category->description)

@section('content')
<div class="site-container" style="padding-top:1.5rem; padding-bottom:3rem">
    <div style="display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start">

        <div>
            {{-- Category Header --}}
            <div style="margin-bottom:1.25rem; padding:1.5rem; border:1px solid {{ $category->color }}40; border-radius:var(--radius-card); background:linear-gradient(135deg, {{ $category->color }}0d 0%, var(--color-surface-800) 100%)">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem">
                    <span style="width:40px; height:40px; border-radius:10px; background:{{ $category->color }}20; border:1px solid {{ $category->color }}40; display:flex; align-items:center; justify-content:center">
                        <svg width="20" height="20" fill="none" stroke="{{ $category->color }}" stroke-width="1.75" viewBox="0 0 24 24"><i data-lucide="{{ $category->icon ?? 'folder' }}"></i></svg>
                    </span>
                    <div>
                        <h1 style="font-size:1.375rem; font-weight:700; color:var(--color-text-primary)">{{ $category->name }}</h1>
                        <p style="font-size:0.875rem; color:var(--color-text-muted)">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</p>
                    </div>
                </div>
                @if($category->description)
                <p style="font-size:0.9rem; color:var(--color-text-secondary)">{{ $category->description }}</p>
                @endif
            </div>

            {{-- Posts --}}
            <div style="display:flex; flex-direction:column; gap:0.5rem">
                @forelse($posts as $post)
                @include('posts._card', ['post' => $post])
                @empty
                <div class="card" style="padding:3rem; text-align:center; color:var(--color-text-muted)">
                    No posts in this category yet.
                </div>
                @endforelse
            </div>

            @if($posts->hasPages())
            <div style="margin-top:1.5rem; display:flex; justify-content:center">
                @include('components.pagination', ['paginator' => $posts])
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="sidebar-desktop" style="position:sticky; top:76px; display:flex; flex-direction:column; gap:1rem">
            <div class="sidebar-section">
                <div class="sidebar-header">All Categories</div>
                @foreach($categories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                   class="sidebar-item {{ $cat->id === $category->id ? 'active' : '' }}">
                    <span style="width:10px; height:10px; border-radius:50%; background:{{ $cat->color }}; flex-shrink:0"></span>
                    {{ $cat->name }}
                    <span class="sidebar-count">{{ $cat->posts_count }}</span>
                </a>
                @endforeach
            </div>

            @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary" style="justify-content:center">
                + New Post in {{ $category->name }}
            </a>
            @endauth
        </aside>
    </div>
</div>
@endsection
