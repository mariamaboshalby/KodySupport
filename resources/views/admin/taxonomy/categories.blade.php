@extends('admin.layout')

@section('title', 'إدارة التصنيفات')
@section('topbar-title', 'إدارة التصنيفات')

@section('content')

<div class="admin-page-header admin-page-header-actions">
    <div>
        <h1>التصنيفات</h1>
        <p>إدارة تصنيفات المحتوى</p>
    </div>
    <button onclick="document.getElementById('addCategoryModal').classList.toggle('hidden')" class="btn btn-primary btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        تصنيف جديد
    </button>
</div>

{{-- Add form (collapsible) --}}
<div id="addCategoryModal" class="hidden" style="margin-bottom:1.5rem">
    <div class="admin-table-wrap" style="padding:1.25rem">
        <h3 style="font-size:0.9375rem; font-weight:600; margin-bottom:1rem; color:var(--color-text-primary)">إضافة تصنيف جديد</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" style="display:flex; gap:0.75rem; align-items:end; flex-wrap:wrap">
            @csrf
            <div style="flex:1; min-width:160px">
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">الاسم *</label>
                <input type="text" name="name" required placeholder="اسم التصنيف" style="width:100%; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.875rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif">
            </div>
            <div style="flex:1; min-width:160px">
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">الوصف</label>
                <input type="text" name="description" placeholder="وصف مختصر" style="width:100%; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.875rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif">
            </div>
            <div>
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">اللون</label>
                <input type="color" name="color" value="#22d3ee" style="height:38px; width:60px; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem; padding:0.25rem; cursor:pointer">
            </div>
            <div>
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">الأيقونة</label>
                <input type="text" name="icon" placeholder="مثال: folder" style="width:90px; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.875rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif">
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="align-self:end">حفظ</button>
        </form>
        @error('name')
            <p style="color:var(--color-danger); font-size:0.8rem; margin-top:0.5rem">{{ $message }}</p>
        @enderror
    </div>
</div>

@if($categories->isEmpty())
    <div style="text-align:center; padding:3rem; color:var(--color-text-muted)">لا توجد تصنيفات بعد</div>
@else
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الوصف</th>
                <th>الحالة</th>
                <th style="text-align:center">المقالات</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td style="color:var(--color-text-muted); font-size:0.75rem">{{ $cat->id }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.5rem">
                        <span class="color-dot" style="background:{{ $cat->color ?? '#22d3ee' }}"></span>
                        <span style="font-weight:500; color:var(--color-text-primary)">{{ $cat->name }}</span>
                    </div>
                    <div style="font-size:0.7rem; color:var(--color-text-muted)">{{ $cat->slug }}</div>
                </td>
                <td style="font-size:0.8125rem; max-width:200px">
                    <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $cat->description ?? '—' }}</div>
                </td>
                <td>
                    <span class="status-badge {{ $cat->is_active ? 'status-published' : 'status-archived' }}">
                        {{ $cat->is_active ? 'نشط' : 'معطل' }}
                    </span>
                </td>
                <td style="text-align:center">{{ $cat->posts_count }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.375rem">
                        {{-- Edit inline --}}
                        <button onclick="toggleEdit('cat-{{ $cat->id }}')" class="btn btn-ghost btn-sm btn-icon" title="تعديل">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="action-form" onsubmit="return confirm('حذف التصنيف؟ سيتم فك ارتباط مقالاته.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-icon" title="حذف">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                            </button>
                        </form>
                    </div>
                    {{-- Edit row (hidden) --}}
                    <form method="POST" action="{{ route('admin.categories.update', $cat) }}" id="cat-{{ $cat->id }}" style="display:none; margin-top:0.5rem">
                        @csrf @method('PATCH')
                        <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap">
                            <input type="text" name="name" value="{{ $cat->name }}" required style="flex:1; min-width:120px; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.35rem 0.6rem; font-size:0.8rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif">
                            <input type="text" name="description" value="{{ $cat->description }}" placeholder="الوصف" style="flex:1; min-width:120px; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.35rem 0.6rem; font-size:0.8rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif">
                            <input type="color" name="color" value="{{ $cat->color ?? '#22d3ee' }}" style="height:32px; width:50px; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.15rem; cursor:pointer">
                            <label style="display:flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:var(--color-text-muted)">
                                <input type="checkbox" name="is_active" value="1" {{ $cat->is_active ? 'checked' : '' }}>
                                نشط
                            </label>
                            <button type="submit" class="btn btn-primary btn-sm">حفظ</button>
                        </div>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@push('scripts')
<script>
function toggleEdit(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', () => {
    // Auto-open add form if there are validation errors
    @if($errors->any())
        document.getElementById('addCategoryModal').classList.remove('hidden');
    @endif
});
</script>
@endpush

@endsection
