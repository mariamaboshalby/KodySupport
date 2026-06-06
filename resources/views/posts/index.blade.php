@extends('layouts.app')

@section('title', 'Community Feed')

@section('content')
<div class="site-container" style="padding-top:1.5rem; padding-bottom:3rem">
    <div style="display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start">

        {{-- ── Main Column ──────────────────────────────────────────────── --}}
        <div>
            {{-- Page Header --}}
            <div class="page-header" style="margin-bottom:1rem">
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem">
                    <div>
                        @if(request('category'))
                            @php $activeCat = $categories->firstWhere('slug', request('category')); @endphp
                            <h1 class="page-title">
                                @if($activeCat)
                                    <span style="color:{{ $activeCat->color }}">{{ $activeCat->name }}</span>
                                @else
                                    All Posts
                                @endif
                            </h1>
                        @elseif(request('type'))
                            <h1 class="page-title" style="text-transform:capitalize">{{ request('type') }}</h1>
                        @elseif(request('q'))
                            <h1 class="page-title">Search: "{{ request('q') }}"</h1>
                        @else
                            <h1 class="page-title" data-i18n="community_feed">آخر المقالات</h1>
                        @endif
                        <p style="font-size:0.875rem; color:var(--color-text-muted); margin-top:0.25rem">
                            {{ $posts->total() }} <span data-i18n="{{ $posts->total() === 1 ? 'posts_found' : 'posts_found_plural' }}">{{ $posts->total() === 1 ? 'مقال' : 'مقالات' }}</span>
                        </p>
                    </div>
                    @auth
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span data-i18n="new_post">مقال جديد</span>
                    </a>
                    @endauth
                </div>
            </div>

            {{-- Sort Bar --}}
            <div class="sort-bar" style="margin-bottom:0.75rem">
                @php $sort = request('sort', 'latest'); @endphp
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}"
                   class="sort-btn {{ $sort === 'latest' ? 'active' : '' }}">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span data-i18n="latest">الأحدث</span>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'top']) }}"
                   class="sort-btn {{ $sort === 'top' ? 'active' : '' }}">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="17 11 12 6 7 11"/><polyline points="17 18 12 13 7 18"/></svg>
                    <span data-i18n="top">الأعلى</span>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'hot']) }}"
                   class="sort-btn {{ $sort === 'hot' ? 'active' : '' }}">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2c0 6-8 8-8 14a8 8 0 0 0 16 0c0-6-8-8-8-14z"/></svg>
                    <span data-i18n="hot">الأكثر نقاشاً</span>
                </a>

                {{-- Type filters --}}
                <div style="margin-left:auto; display:flex; gap:0.25rem; flex-wrap:wrap">
                    @foreach(['announcement' => '#f59e0b', 'documentation' => '#8b5cf6', 'changelog' => '#10b981'] as $type => $color)
                    <a href="{{ request()->fullUrlWithQuery(['type' => request('type') === $type ? null : $type]) }}"
                       class="badge badge-type-{{ $type }}"
                       style="{{ request('type') === $type ? 'opacity:1; box-shadow:0 0 8px rgba(0,0,0,0.3)' : 'opacity:0.7' }}">
                        {{ ucfirst($type) }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Pinned Posts (only on first page, no active filters) --}}
            @if(!request('q') && !request('category') && !request('type') && $posts->currentPage() === 1 && $pinned->count())
            <div style="margin-bottom:0.75rem">
                @foreach($pinned as $pin)
                @include('posts._card', ['post' => $pin])
                @endforeach
            </div>
            @endif

            {{-- Post Feed --}}
            <div style="display:flex; flex-direction:column; gap:0.5rem">
                @forelse($posts as $post)
                    @include('posts._card', ['post' => $post])
                @empty
                    <div class="card" style="padding:3rem; text-align:center">
                        <svg width="48" height="48" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 1rem"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <p style="color:var(--color-text-muted); font-size:0.9375rem">No posts found.</p>
                        @auth
                        <a href="{{ route('posts.create') }}" class="btn btn-primary" style="margin-top:1rem">Be the first to post</a>
                        @endauth
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
            <div style="margin-top:1.5rem; display:flex; justify-content:center">
                @include('components.pagination', ['paginator' => $posts])
            </div>
            @endif
        </div>

        {{-- ── Sidebar ───────────────────────────────────────────────────── --}}
        <aside class="sidebar-desktop" style="position:sticky; top:76px; display:flex; flex-direction:column; gap:1rem">

            {{-- About --}}
            <div class="hero-banner">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem">
                    <div class="brand-icon" style="width:36px; height:36px; border-radius:8px">
                        <svg width="18" height="18" fill="none" stroke="#020b18" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    </div>
                    <div>
                        <div style="font-weight:700; color:var(--color-text-primary)">{{ config('app.name') }}</div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted)">Community Hub</div>
                    </div>
                </div>
                <p style="font-size:0.8125rem; color:var(--color-text-secondary); line-height:1.6; margin-bottom:1rem">
                    Announcements, documentation, changelogs, and community discussions — all in one place.
                </p>
                @guest
                <div style="display:flex; gap:0.5rem">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="flex:1; justify-content:center">Join</a>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm" style="flex:1; justify-content:center">Sign in</a>
                </div>
                @endguest
            </div>

            {{-- Categories --}}
            <div class="sidebar-section">
                <div class="sidebar-header">Categories</div>
                @foreach($categories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                   class="sidebar-item {{ request('category') === $cat->slug ? 'active' : '' }}">
                    <span style="width:10px; height:10px; border-radius:50%; background:{{ $cat->color }}; flex-shrink:0"></span>
                    {{ $cat->name }}
                    <span class="sidebar-count">{{ $cat->posts_count }}</span>
                </a>
                @endforeach
            </div>

            {{-- Popular Posts --}}
            @if($popular->count())
            <div class="sidebar-section">
                <div class="sidebar-header">Popular This Week</div>
                @foreach($popular as $pop)
                <a href="{{ route('posts.show', $pop->slug) }}" class="sidebar-item" style="flex-direction:column; align-items:flex-start; gap:0.25rem">
                    <span style="font-size:0.8125rem; color:var(--color-text-primary); line-height:1.4">{{ Str::limit($pop->title, 60) }}</span>
                    <span style="font-size:0.75rem; color:var(--color-text-muted)">
                        {{ number_format($pop->views_count) }} views · {{ $pop->votes_count }} votes
                    </span>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Post Types --}}
            <div class="sidebar-section">
                <div class="sidebar-header">Browse By Type</div>
                @foreach(['post' => ['label'=>'Discussion','color'=>'#22d3ee'], 'announcement' => ['label'=>'Announcements','color'=>'#f59e0b'], 'documentation' => ['label'=>'Documentation','color'=>'#8b5cf6'], 'changelog' => ['label'=>'Changelog','color'=>'#10b981']] as $type => $meta)
                <a href="{{ route('posts.index') }}?type={{ $type }}" class="sidebar-item">
                    <span style="width:8px; height:8px; border-radius:2px; background:{{ $meta['color'] }}; flex-shrink:0"></span>
                    {{ $meta['label'] }}
                </a>
                @endforeach
            </div>
        </aside>
    </div>
</div>
@endsection
