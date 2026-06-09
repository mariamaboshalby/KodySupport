@extends('admin.layout')

@section('title', 'إدارة التعليقات')
@section('topbar-title', 'إدارة التعليقات')

@section('content')

<div class="admin-page-header">
    <h1>التعليقات</h1>
    <p>مراجعة وإدارة جميع التعليقات</p>
</div>

<form method="GET" class="filter-bar">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="ابحث في نص التعليق..." style="flex:1; min-width:200px">
    <select name="status">
        <option value="">الكل</option>
        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>نشط</option>
        <option value="trashed"  {{ request('status') === 'trashed'  ? 'selected' : '' }}>محذوف</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">بحث</button>
    @if(request()->hasAny(['q','status']))
        <a href="{{ route('admin.comments.index') }}" class="btn btn-outline btn-sm">مسح</a>
    @endif
    <span style="margin-right:auto; font-size:0.8125rem; color:var(--color-text-muted)">{{ $comments->total() }} تعليق</span>
</form>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-hide-mobile">#</th>
                <th>الكاتب</th>
                <th class="col-hide-mobile">المقال</th>
                <th>التعليق</th>
                <th class="col-hide-mobile" style="text-align:center">الأصوات</th>
                <th class="col-hide-mobile">التاريخ</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comments as $comment)
            <tr>
                <td class="col-hide-mobile" style="color:var(--color-text-muted); font-size:0.75rem">{{ $comment->id }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.5rem">
                        <img src="{{ $comment->author?->avatar_url }}" alt="" style="width:28px;height:28px;border-radius:50%;flex-shrink:0">
                        <span style="font-size:0.875rem; color:var(--color-text-primary)">{{ $comment->author?->display_name }}</span>
                    </div>
                </td>
                <td class="col-hide-mobile" style="max-width:160px">
                    @if($comment->post)
                    <a href="{{ route('posts.show', $comment->post) }}" target="_blank" style="font-size:0.8125rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block">
                        {{ $comment->post->title }}
                    </a>
                    @else
                    <span style="color:var(--color-text-muted); font-size:0.8rem">مقال محذوف</span>
                    @endif
                </td>
                <td style="max-width:280px">
                    <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:0.875rem">
                        {{ $comment->body }}
                    </div>
                    @if($comment->parent_id)
                        <div style="font-size:0.7rem; color:var(--color-text-muted); margin-top:0.15rem">↩ رد</div>
                    @endif
                    @if($comment->trashed())
                        <span class="status-badge status-trashed" style="margin-top:0.25rem">محذوف</span>
                    @endif
                </td>
                <td class="col-hide-mobile" style="text-align:center; font-size:0.8125rem">{{ $comment->votes_count }}</td>
                <td class="col-hide-mobile" style="font-size:0.75rem; white-space:nowrap">{{ $comment->created_at->format('Y/m/d') }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.25rem">
                        @if($comment->trashed())
                            <form method="POST" action="{{ route('admin.comments.restore', $comment->id) }}" class="action-form">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline btn-sm" title="استعادة" style="border-color:rgba(16,185,129,0.4); color:#34d399">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.64"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.comments.force-delete', $comment->id) }}" class="action-form" onsubmit="return confirm('حذف نهائي؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="حذف نهائي">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="action-form" onsubmit="return confirm('حذف التعليق؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="حذف">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding:2rem; color:var(--color-text-muted)">لا توجد تعليقات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($comments->hasPages())
<div style="margin-top:1rem; display:flex; justify-content:center">
    {{ $comments->withQueryString()->links() }}
</div>
@endif

@endsection
