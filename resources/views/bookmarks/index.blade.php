@extends('layouts.app')
@section('title', 'Saved Posts')

@section('content')
<div class="site-container" style="padding-top:1.5rem; padding-bottom:3rem; max-width:800px">
    <div style="margin-bottom:1.25rem">
        <h1 class="page-title">Saved Posts</h1>
        <p style="color:var(--color-text-muted); font-size:0.875rem; margin-top:0.25rem">{{ $posts->total() }} saved</p>
    </div>

    <div style="display:flex; flex-direction:column; gap:0.5rem">
        @forelse($posts as $bookmark)
        @include('posts._card', ['post' => $bookmark->post])
        @empty
        <div class="card" style="padding:3rem; text-align:center">
            <svg width="48" height="48" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 1rem"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
            <p style="color:var(--color-text-muted)">No saved posts yet.</p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top:1rem; display:inline-flex">Browse posts</a>
        </div>
        @endforelse
    </div>

    @if($posts->hasPages())
    <div style="margin-top:1.5rem; display:flex; justify-content:center">
        @include('components.pagination', ['paginator' => $posts])
    </div>
    @endif
</div>
@endsection
