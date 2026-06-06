@php $userVote = $comment->userVote(auth()->user()); @endphp

<div class="comment-item {{ $depth > 0 ? 'reply' : '' }}" id="comment-{{ $comment->id }}">
    <div style="display:flex; align-items:flex-start; gap:0.75rem">
        <img src="{{ $comment->author->avatar_url }}" alt="{{ $comment->author->display_name }}" class="avatar avatar-sm">
        <div style="flex:1; min-width:0">
            <div style="display:flex; align-items:center; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.375rem">
                <span style="font-weight:600; font-size:0.875rem; color:var(--color-text-primary)">{{ $comment->author->display_name }}</span>
                <span style="font-size:0.8125rem; color:var(--color-text-muted)">{{ $comment->created_at->diffForHumans() }}</span>
                @if($comment->is_accepted)
                <span class="badge" style="background:rgba(16,185,129,0.1); color:#34d399; border-color:rgba(16,185,129,0.2); font-size:0.7rem">✓ Accepted</span>
                @endif
            </div>

            <div class="comment-body">{{ $comment->body }}</div>

            {{-- Comment Actions --}}
            <div style="display:flex; align-items:center; gap:0.75rem; margin-top:0.5rem">
                @auth
                <button class="vote-btn {{ $userVote === 1 ? 'active-up' : '' }}"
                        style="padding:0.2rem 0.5rem; border-radius:4px; border:1px solid var(--color-slate-border); font-size:0.75rem; display:flex; align-items:center; gap:0.3rem"
                        onclick="voteComment({{ $comment->id }}, 1, this)">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                    <span id="votes-comment-{{ $comment->id }}">{{ $comment->votes_count }}</span>
                </button>

                @if($depth < 1 && !$post->is_locked)
                <button onclick="toggleReply({{ $comment->id }})"
                        style="font-size:0.8125rem; color:var(--color-text-muted); background:none; border:none; cursor:pointer; transition:color 0.15s; padding:0"
                        onmouseover="this.style.color='var(--color-cyan-400)'"
                        onmouseout="this.style.color='var(--color-text-muted)'"
                        data-i18n="reply">رد</button>
                @endif

                @can('delete', $comment)
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                      onsubmit="return confirm(window.__t ? window.__t('delete_confirm') : 'Delete?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="font-size:0.8125rem; color:var(--color-text-muted); background:none; border:none; cursor:pointer; transition:color 0.15s; padding:0"
                            onmouseover="this.style.color='var(--color-danger)'"
                            onmouseout="this.style.color='var(--color-text-muted)'"
                            data-i18n="delete">حذف</button>
                </form>
                @endcan
                @endauth
            </div>

            {{-- Inline Reply Form --}}
            @auth
            @if($depth < 1 && !$post->is_locked)
            <div id="reply-form-{{ $comment->id }}" style="display:none; margin-top:0.875rem">
                <form action="{{ route('comments.store', $post->slug) }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="body" rows="3" class="form-textarea" style="min-height:80px; font-size:0.875rem; margin-bottom:0.5rem"
                              data-i18n-placeholder="share_thoughts"
                              placeholder="شارك رأيك…" required></textarea>
                    <div style="display:flex; gap:0.5rem; justify-content:flex-end">
                        <button type="button" onclick="toggleReply({{ $comment->id }})" class="btn btn-ghost btn-sm" data-i18n="cancel">إلغاء</button>
                        <button type="submit" class="btn btn-primary btn-sm" data-i18n="reply">رد</button>
                    </div>
                </form>
            </div>
            @endif
            @endauth
        </div>
    </div>

    {{-- Nested Replies --}}
    @if($comment->replies->count() && $depth < 1)
    <div style="margin-top:0.5rem; margin-inline-start:2.5rem; border-inline-start:2px solid var(--color-slate-border); padding-inline-start:1rem">
        @foreach($comment->replies as $reply)
        @include('posts._comment', ['comment' => $reply, 'post' => $post, 'depth' => $depth + 1])
        @endforeach
    </div>
    @endif
</div>
