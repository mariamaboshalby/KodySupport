@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->excerpt ?? Str::limit(strip_tags($post->body), 160))

@section('content')
<div class="site-container" style="padding-top:1.5rem; padding-bottom:3rem">
    <div style="display:grid; grid-template-columns:1fr 280px; gap:1.5rem; align-items:start">

        {{-- ── Main Content ──────────────────────────────────────────────── --}}
        <div>

            {{-- Breadcrumb --}}
            <nav style="display:flex; align-items:center; gap:0.5rem; font-size:0.8125rem; color:var(--color-text-muted); margin-bottom:1.25rem">
                <a href="{{ route('home') }}" style="color:var(--color-text-muted); text-decoration:none; transition:color 0.15s">Home</a>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                @if($post->category)
                <a href="{{ route('categories.show', $post->category->slug) }}" style="color:{{ $post->category->color }}; text-decoration:none">{{ $post->category->name }}</a>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                @endif
                <span style="color:var(--color-text-secondary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:300px">{{ $post->title }}</span>
            </nav>

            {{-- Post Article --}}
            <article class="card" style="overflow:hidden">

                <div style="padding:1.75rem 2rem">

                    {{-- Badges --}}
                    <div style="display:flex; align-items:center; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem">
                        <span class="badge badge-type-{{ $post->type }}">{{ $post->type_label }}</span>
                        @if($post->is_pinned)<span class="badge badge-pinned">Pinned</span>@endif
                        @if($post->is_locked)<span class="badge badge-locked">Locked</span>@endif
                        @foreach($post->tags as $tag)
                        <a href="{{ route('posts.index') }}?tag={{ $tag->slug }}" class="tag-pill">{{ $tag->name }}</a>
                        @endforeach
                    </div>

                    {{-- Title --}}
                    <h1 style="font-size:1.75rem; font-weight:700; line-height:1.3; color:var(--color-text-primary); letter-spacing:-0.02em; margin-bottom:1rem">
                        {{ $post->title }}
                    </h1>

                    {{-- Author Meta --}}
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; padding-bottom:1.25rem; border-bottom:1px solid var(--color-slate-border); margin-bottom:1.5rem">
                        <div style="display:flex; align-items:center; gap:0.75rem">
                            <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->display_name }}" class="avatar avatar-lg">
                            <div>
                                <div style="font-weight:600; color:var(--color-text-primary)">{{ $post->author->display_name }}</div>
                                <div style="font-size:0.8125rem; color:var(--color-text-muted)">
                                    {{ $post->published_at?->format('M j, Y') }}
                                    <span style="margin:0 0.4rem; color:var(--color-slate-muted)">·</span>
                                    {{ $post->reading_time }} min read
                                    <span style="margin:0 0.4rem; color:var(--color-slate-muted)">·</span>
                                    {{ number_format($post->views_count) }} views
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex; align-items:center; gap:0.5rem">
                            {{-- Bookmark --}}
                            @auth
                            <form action="{{ route('bookmarks.toggle', $post->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm"
                                        style="{{ $post->isBookmarkedBy(auth()->user()) ? 'border-color:var(--color-cyan-500);color:var(--color-cyan-400)' : '' }}">
                                    <svg width="13" height="13" fill="{{ $post->isBookmarkedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                    {{ $post->isBookmarkedBy(auth()->user()) ? 'Saved' : 'Save' }}
                                </button>
                            </form>
                            @endauth

                            {{-- Edit / Delete (author or mod) --}}
                            @can('update', $post)
                            <a href="{{ route('posts.edit', $post->slug) }}" class="btn btn-outline btn-sm">Edit</a>
                            @endcan
                            @can('delete', $post)
                            <form action="{{ route('posts.destroy', $post->slug) }}" method="POST"
                                  onsubmit="return confirm(window.__t ? window.__t('delete_confirm') : 'Delete this post?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            @endcan
                        </div>
                    </div>

                    {{-- Post Body --}}
                    <div class="prose" style="max-width:none">
                        {!! $post->rendered_body !!}
                    </div>

                    {{-- Cover Image --}}
                    @if($post->cover_image)
                    <div style="margin-top:1.5rem; border-radius:10px; overflow:hidden; max-height:420px">
                        <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}"
                             style="width:100%; height:100%; object-fit:cover; display:block">
                    </div>
                    @endif
                </div>

                {{-- Vote Footer --}}
                <div style="padding:1rem 2rem; border-top:1px solid var(--color-slate-border); background:var(--color-surface-900); display:flex; align-items:center; gap:1rem">
                    <span style="font-size:0.875rem; color:var(--color-text-muted)" data-i18n="was_helpful">هل كان مفيداً؟</span>
                    @auth
                    <div style="display:flex; align-items:center; gap:0.5rem">
                        <button class="btn btn-outline btn-sm vote-btn {{ $post->userVote(auth()->user()) === 1 ? 'active-up' : '' }}"
                                onclick="votePost({{ $post->id }}, 1, this)"
                                style="{{ $post->userVote(auth()->user()) === 1 ? 'border-color:var(--color-cyan-500);color:var(--color-cyan-400)' : '' }}">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                            <span data-i18n="upvote">أعجبني</span> &nbsp;<span id="votes-post-{{ $post->id }}">{{ $post->votes_count }}</span>
                        </button>
                        <button class="btn btn-outline btn-sm vote-btn {{ $post->userVote(auth()->user()) === -1 ? 'active-down' : '' }}"
                                onclick="votePost({{ $post->id }}, -1, this)"
                                style="{{ $post->userVote(auth()->user()) === -1 ? 'border-color:var(--color-danger);color:var(--color-danger)' : '' }}">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                            <span data-i18n="downvote">لم يعجبني</span>
                        </button>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm" data-i18n="sign_in_to_vote">سجل دخولك للتصويت</a>
                    @endauth
                </div>
            </article>

            {{-- ── Comments Section ────────────────────────────────────────── --}}
            <div id="comments" style="margin-top:2rem">
                <h2 style="font-size:1.125rem; font-weight:700; color:var(--color-text-primary); margin-bottom:1.25rem">
                    {{ $post->comments_count }} <span data-i18n="{{ $post->comments_count === 1 ? 'comments' : 'comments_plural' }}">{{ $post->comments_count === 1 ? 'تعليق' : 'تعليقات' }}</span>
                </h2>

                @auth
                @if(!$post->is_locked)
                <div class="card" style="padding:1.25rem; margin-bottom:1.5rem">
                    <div style="display:flex; gap:0.75rem">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->display_name }}" class="avatar avatar-md" style="flex-shrink:0">
                        <form action="{{ route('comments.store', $post->slug) }}" method="POST" style="flex:1">
                            @csrf
                            <textarea name="body" rows="3" class="form-textarea" style="min-height:90px; margin-bottom:0.75rem"
                                      data-i18n-placeholder="share_thoughts"
                                      placeholder="شارك رأيك أو اطرح سؤالاً…" required>{{ old('body') }}</textarea>
                            @error('body')
                            <p class="form-error" style="margin-bottom:0.5rem">{{ $message }}</p>
                            @enderror
                            <div style="display:flex; justify-content:flex-end">
                                <button type="submit" class="btn btn-primary btn-sm" data-i18n="post_comment">إرسال التعليق</button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-warning" style="margin-bottom:1.5rem">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span data-i18n="thread_locked">هذا الخيط مقفل. لا يمكن إضافة تعليقات جديدة.</span>
                </div>
                @endif
                @else
                <div class="alert alert-info" style="margin-bottom:1.5rem">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><a href="{{ route('login') }}" data-i18n="sign_in">تسجيل الدخول</a> <span data-i18n="sign_in_to_discuss">للمشاركة في النقاش.</span></span>
                </div>
                @endauth

                {{-- Comment List --}}
                <div class="comment-thread">
                    @forelse($post->comments as $comment)
                    @include('posts._comment', ['comment' => $comment, 'post' => $post, 'depth' => 0])
                    @empty
                    <div style="text-align:center; padding:2rem; color:var(--color-text-muted); font-size:0.9375rem">
                        No comments yet. Be the first to start the conversation.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── Sidebar ───────────────────────────────────────────────────── --}}
        <aside class="sidebar-desktop" style="position:sticky; top:76px; display:flex; flex-direction:column; gap:1rem">

            {{-- Post stats --}}
            <div class="sidebar-section">
                <div class="sidebar-header" data-i18n="post_stats">إحصائيات المقال</div>
                <div style="padding:1rem; display:grid; grid-template-columns:1fr 1fr; gap:0.75rem">
                    <div style="text-align:center">
                        <div style="font-size:1.375rem; font-weight:700; color:var(--color-cyan-400)">{{ number_format($post->votes_count) }}</div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted)" data-i18n="votes">تصويت</div>
                    </div>
                    <div style="text-align:center">
                        <div style="font-size:1.375rem; font-weight:700; color:var(--color-cyan-400)">{{ number_format($post->views_count) }}</div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted)" data-i18n="views">مشاهدة</div>
                    </div>
                    <div style="text-align:center">
                        <div style="font-size:1.375rem; font-weight:700; color:var(--color-cyan-400)">{{ number_format($post->comments_count) }}</div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted)" data-i18n="comments_plural">تعليقات</div>
                    </div>
                    <div style="text-align:center">
                        <div style="font-size:1.375rem; font-weight:700; color:var(--color-cyan-400)">{{ $post->reading_time }}m</div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted)" data-i18n="read_min">د قراءة</div>
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            @if($post->tags->count())
            <div class="sidebar-section">
                <div class="sidebar-header" data-i18n="tags">الوسوم</div>
                <div style="padding:0.875rem; display:flex; flex-wrap:wrap; gap:0.5rem">
                    @foreach($post->tags as $tag)
                    <a href="{{ route('posts.index') }}?tag={{ $tag->slug }}" class="tag-pill">{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Related Posts --}}
            @if($related->count())
            <div class="sidebar-section">
                <div class="sidebar-header" data-i18n="related_posts">مقالات ذات صلة</div>
                @foreach($related as $rel)
                <a href="{{ route('posts.show', $rel->slug) }}" class="sidebar-item" style="flex-direction:column; align-items:flex-start; gap:0.25rem">
                    <span class="badge badge-type-{{ $rel->type }}" style="font-size:0.7rem; padding:0.1rem 0.4rem">{{ $rel->type_label }}</span>
                    <span style="font-size:0.8125rem; color:var(--color-text-primary); line-height:1.4">{{ Str::limit($rel->title, 55) }}</span>
                </a>
                @endforeach
            </div>
            @endif
        </aside>
    </div>
</div>
@endsection
