@extends('admin.layout')

@section('title', 'إدارة الوسوم')
@section('topbar-title', 'إدارة الوسوم')

@section('content')

<div class="admin-page-header admin-page-header-actions">
    <div>
        <h1>الوسوم</h1>
        <p>إدارة الوسوم المستخدمة في تصنيف المقالات</p>
    </div>
    <button onclick="document.getElementById('addTagModal').classList.toggle('hidden')" class="btn btn-primary btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        وسم جديد
    </button>
</div>

{{-- Add form --}}
<div id="addTagModal" class="{{ $errors->any() ? '' : 'hidden' }}" style="margin-bottom:1.5rem">
    <div class="admin-table-wrap" style="padding:1.25rem">
        <h3 style="font-size:0.9375rem; font-weight:600; margin-bottom:1rem; color:var(--color-text-primary)">إضافة وسم جديد</h3>
        <form method="POST" action="{{ route('admin.tags.store') }}" style="display:flex; gap:0.75rem; align-items:end; flex-wrap:wrap">
            @csrf
            <div>
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">الاسم *</label>
                <input type="text" name="name" required placeholder="اسم الوسم" value="{{ old('name') }}" style="background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.875rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif; min-width:200px">
            </div>
            <div>
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">اللون</label>
                <input type="color" name="color" value="{{ old('color', '#22d3ee') }}" style="height:38px; width:60px; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem; padding:0.25rem; cursor:pointer">
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="align-self:end">حفظ</button>
        </form>
        @error('name')
            <p style="color:var(--color-danger); font-size:0.8rem; margin-top:0.5rem">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Tags grid --}}
<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:0.75rem">
    @forelse($tags as $tag)
    <div class="card" style="padding:1rem; display:flex; flex-direction:column; gap:0.75rem">
        <div style="display:flex; align-items:center; justify-content:space-between">
            <div style="display:flex; align-items:center; gap:0.5rem">
                <span class="color-dot" style="background:{{ $tag->color ?? '#22d3ee' }}; width:14px; height:14px"></span>
                <span style="font-weight:600; color:var(--color-text-primary)">{{ $tag->name }}</span>
            </div>
            <span style="font-size:0.75rem; color:var(--color-text-muted); background:var(--color-surface-700); padding:0.15rem 0.5rem; border-radius:999px">{{ $tag->posts_count }} مقال</span>
        </div>
        <div style="font-size:0.75rem; color:var(--color-text-muted)">slug: {{ $tag->slug }}</div>

        {{-- Edit form --}}
        <form method="POST" action="{{ route('admin.tags.update', $tag) }}" style="display:flex; gap:0.5rem; align-items:center">
            @csrf @method('PATCH')
            <input type="text" name="name" value="{{ $tag->name }}" required style="flex:1; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.3rem 0.6rem; font-size:0.8rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif">
            <input type="color" name="color" value="{{ $tag->color ?? '#22d3ee' }}" style="height:30px; width:42px; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.1rem; cursor:pointer">
            <button type="submit" class="btn btn-outline btn-sm" title="حفظ">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </button>
        </form>

        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" class="action-form" onsubmit="return confirm('حذف الوسم؟')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" style="width:100%; justify-content:center">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                حذف الوسم
            </button>
        </form>
    </div>
    @empty
    <div style="grid-column:1/-1; text-align:center; padding:3rem; color:var(--color-text-muted)">لا توجد وسوم بعد</div>
    @endforelse
</div>

@endsection
