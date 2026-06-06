@php
    $userVote = $post->userVote(auth()->user());

    // Resolve thumbnail: cover image first, then first YouTube in body
    $thumbnail = null;
    if ($post->cover_image) {
        $thumbnail = ['type' => 'image', 'src' => asset('storage/' . $post->cover_image)];
    } elseif (preg_match('/\[youtube:([A-Za-z0-9_-]{11})\]/', $post->body, $m)) {
        $thumbnail = ['type' => 'youtube', 'src' => "https://img.youtube.com/vi/{$m[1]}/mqdefault.jpg", 'id' => $m[1]];
    }
@endphp

<div class="post-card {{ $post->is_pinned ? 'pinned' : '' }}" id="post-{{ $post->id }}">

    {{-- Vote Column --}}
    <div class="vote-col">
        @auth
        <button class="vote-btn {{ $userVote === 1 ? 'active-up' : '' }}"
                onclick="votePost({{ $post->id }}, 1, this)"
                data-i18n-title="upvote" title="أعجبني">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
        </button>
        @else
        <a href="{{ route('login') }}" class="vote-btn" data-i18n-title="sign_in_to_vote" title="سجل دخولك للتصويت">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
        </a>
        @endauth

        <span class="vote-count" id="votes-post-{{ $post->id }}">{{ number_format($post->votes_count) }}</span>

        @auth
        <button class="vote-btn {{ $userVote === -1 ? 'active-down' : '' }}"
                onclick="votePost({{ $post->id }}, -1, this)"
                data-i18n-title="downvote" title="لم يعجبني">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        @else
        <a href="{{ route('login') }}" class="vote-btn" data-i18n-title="sign_in_to_vote" title="سجل دخولك للتصويت">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </a>
        @endauth
    </div>

    {{-- Post Content --}}
    <div class="post-content">
        {{-- Meta row --}}
        <div class="post-meta">
            <span class="badge badge-type-{{ $post->type }}" data-i18n="post_type_{{ $post->type }}">{{ $post->type_label }}</span>

            @if($post->is_pinned)
            <span class="badge badge-pinned">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="17" x2="12" y2="22"/><path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V17z"/></svg>
                <span data-i18n="pinned">مثبت</span>
            </span>
            @endif
            @if($post->is_locked)
            <span class="badge badge-locked">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span data-i18n="locked">مقفل</span>
            </span>
            @endif

            @if($post->category)
            <a href="{{ route('categories.show', $post->category->slug) }}"
               style="color:{{ $post->category->color }}; font-weight:500; font-size:0.8125rem; text-decoration:none">
                {{ $post->category->name }}
            </a>
            @endif

            <span style="color:var(--color-slate-muted)">·</span>
            <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->display_name }}" class="avatar avatar-sm">
            <span>{{ $post->author->display_name }}</span>
            <span style="color:var(--color-slate-muted)">·</span>
            <span title="{{ $post->published_at?->format('M j, Y g:i A') }}">{{ $post->published_at?->diffForHumans() }}</span>
        </div>

        {{-- Title --}}
        <a href="{{ route('posts.show', $post->slug) }}" class="post-title" style="display:block; text-decoration:none">
            {{ $post->title }}
        </a>

        {{-- Excerpt --}}
        @if($post->excerpt)
        <p class="post-excerpt">{{ $post->excerpt }}</p>
        @endif

        {{-- Footer: stats + tags --}}
        <div class="post-footer">
            <a href="{{ route('posts.show', $post->slug) }}#comments" class="post-stat" style="color:var(--color-text-muted); text-decoration:none">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                {{ number_format($post->comments_count) }}
            </a>

            <span class="post-stat">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                {{ number_format($post->views_count) }}
            </span>

            <span class="post-stat">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $post->reading_time }}<span data-i18n="read_time"> د قراءة</span>
            </span>

            @foreach($post->tags->take(3) as $tag)
            <a href="{{ route('posts.index') }}?tag={{ $tag->slug }}" class="tag-pill">{{ $tag->name }}</a>
            @endforeach

            {{-- Bookmark --}}
            @auth
            <form action="{{ route('bookmarks.toggle', $post->id) }}" method="POST" style="margin-left:auto">
                @csrf
                <button type="submit" class="btn btn-ghost btn-icon btn-sm"
                        data-i18n-title="{{ $post->isBookmarkedBy(auth()->user()) ? 'saved' : 'save_post' }}"
                        title="{{ $post->isBookmarkedBy(auth()->user()) ? 'محفوظ' : 'حفظ' }}"
                        style="color:{{ $post->isBookmarkedBy(auth()->user()) ? 'var(--color-cyan-400)' : 'var(--color-text-muted)' }}">
                    <svg width="13" height="13" fill="{{ $post->isBookmarkedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                </button>
            </form>
            @endauth
        </div>
    </div>

    {{-- Thumbnail (cover image or YouTube) --}}
    @if($thumbnail)
    <a href="{{ route('posts.show', $post->slug) }}"
       style="display:block; flex-shrink:0; width:120px; align-self:stretch; overflow:hidden; border-inline-start:1px solid var(--color-slate-border); position:relative; text-decoration:none">
        <img src="{{ $thumbnail['src'] }}" alt="{{ $post->title }}"
             style="width:100%; height:100%; object-fit:cover; display:block;">
        @if($thumbnail['type'] === 'youtube')
        <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.35)">
            <div style="width:32px; height:32px; background:rgba(220,38,38,0.9); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                <svg width="12" height="12" fill="white" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
        </div>
        @endif
    </a>
    @endif
</div>
