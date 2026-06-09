@extends('admin.layout')

@section('title', 'إدارة المقالات')
@section('topbar-title', 'إدارة المقالات')

@section('content')

<div class="admin-page-header">
    <h1>المقالات</h1>
    <p>إدارة جميع المقالات وحالاتها</p>
</div>

{{-- Filter bar --}}
<form method="GET" class="filter-bar">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="ابحث في العنوان أو المحتوى..." style="flex:1; min-width:200px">
    <select name="status">
        <option value="">كل الحالات</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>منشور</option>
        <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>مسودة</option>
        <option value="archived"  {{ request('status') === 'archived'  ? 'selected' : '' }}>مؤرشف</option>
        <option value="trashed"   {{ request('status') === 'trashed'   ? 'selected' : '' }}>محذوف</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">بحث</button>
    @if(request()->hasAny(['q','status','type']))
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline btn-sm">مسح</a>
    @endif
    <span style="margin-right:auto; font-size:0.8125rem; color:var(--color-text-muted)">{{ $posts->total() }} مقال</span>
</form>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-hide-mobile">#</th>
                <th>العنوان</th>
                <th class="col-hide-mobile">الكاتب</th>
                <th>الحالة</th>
                <th class="col-hide-mobile">النوع</th>
                <th class="col-hide-mobile" style="text-align:center">المشاهدات</th>
                <th class="col-hide-mobile" style="text-align:center">الأصوات</th>
                <th class="col-hide-mobile" style="text-align:center">التعليقات</th>
                <th class="col-hide-mobile">التاريخ</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td class="col-hide-mobile" style="color:var(--color-text-muted); font-size:0.75rem">{{ $post->id }}</td>
                <td style="max-width:220px">
                    <div style="display:flex; align-items:center; gap:0.375rem">
                        @if($post->is_pinned)
                        <svg width="12" height="12" fill="#f59e0b" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                        @endif
                        @if($post->is_locked)
                        <svg width="12" height="12" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        @endif
                    </div>
                    <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--color-text-primary); font-weight:500; font-size:0.875rem">
                        {{ $post->title }}
                    </div>
                    @if($post->category)
                    <div style="font-size:0.7rem; color:var(--color-text-muted)">{{ $post->category->name }}</div>
                    @endif
                </td>
                <td class="col-hide-mobile" style="font-size:0.8125rem">{{ $post->author->display_name }}</td>
                <td>
                    @if($post->trashed())
                        <span class="status-badge status-trashed">محذوف</span>
                    @else
                        <span class="status-badge status-{{ $post->status }}">
                            {{ match($post->status) { 'published' => 'منشور', 'draft' => 'مسودة', 'archived' => 'مؤرشف', default => $post->status } }}
                        </span>
                    @endif
                </td>
                <td class="col-hide-mobile">
                    <span style="background:rgba(34,211,238,0.08); color:var(--color-cyan-400); font-size:0.7rem; padding:0.15rem 0.5rem; border-radius:999px; border:1px solid rgba(34,211,238,0.15)">{{ $post->type }}</span>
                </td>
                <td class="col-hide-mobile" style="text-align:center; font-size:0.8125rem">{{ number_format($post->views_count) }}</td>
                <td class="col-hide-mobile" style="text-align:center; font-size:0.8125rem">{{ $post->votes_count }}</td>
                <td class="col-hide-mobile" style="text-align:center; font-size:0.8125rem">{{ $post->comments_count }}</td>
                <td class="col-hide-mobile" style="font-size:0.75rem; white-space:nowrap">{{ $post->created_at->format('Y/m/d') }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.25rem; flex-wrap:wrap">
                        @if($post->trashed())
                            {{-- Restore --}}
                            <form method="POST" action="{{ route('admin.posts.restore', $post->id) }}" class="action-form">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline btn-sm" title="استعادة" style="border-color:rgba(16,185,129,0.4); color:#34d399">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.64"/></svg>
                                </button>
                            </form>
                            {{-- Force Delete --}}
                            <form method="POST" action="{{ route('admin.posts.force-delete', $post->id) }}" class="action-form" onsubmit="return confirm('حذف نهائي؟ لا يمكن التراجع!')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="حذف نهائي">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        @else
                            {{-- View --}}
                            <a href="{{ route('posts.show', $post) }}" target="_blank" class="btn btn-ghost btn-sm btn-icon" title="عرض">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-ghost btn-sm btn-icon" title="تعديل">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            {{-- Pin --}}
                            <form method="POST" action="{{ route('admin.posts.pin', $post) }}" class="action-form">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm btn-icon" title="{{ $post->is_pinned ? 'إلغاء التثبيت' : 'تثبيت' }}" style="{{ $post->is_pinned ? 'color:#f59e0b' : '' }}">
                                    <svg width="13" height="13" fill="{{ $post->is_pinned ? '#f59e0b' : 'none' }}" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                                </button>
                            </form>
                            {{-- Lock --}}
                            <form method="POST" action="{{ route('admin.posts.lock', $post) }}" class="action-form">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm btn-icon" title="{{ $post->is_locked ? 'فتح القفل' : 'قفل' }}">
                                    @if($post->is_locked)
                                    <svg width="13" height="13" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    @else
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                                    @endif
                                </button>
                            </form>
                            {{-- Change Status --}}
                            <form method="POST" action="{{ route('admin.posts.status', $post) }}" class="action-form" style="display:inline-flex; align-items:center; gap:0.25rem">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.25rem 0.4rem; font-size:0.7rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif">
                                    <option value="published" {{ $post->status === 'published' ? 'selected' : '' }}>منشور</option>
                                    <option value="draft"     {{ $post->status === 'draft'     ? 'selected' : '' }}>مسودة</option>
                                    <option value="archived"  {{ $post->status === 'archived'  ? 'selected' : '' }}>مؤرشف</option>
                                </select>
                            </form>
                            {{-- Soft Delete --}}
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="action-form" onsubmit="return confirm('حذف المقال؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="حذف">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center; padding:2rem; color:var(--color-text-muted)">لا توجد مقالات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($posts->hasPages())
<div style="margin-top:1rem; display:flex; justify-content:center">
    {{ $posts->withQueryString()->links() }}
</div>
@endif

@endsection
