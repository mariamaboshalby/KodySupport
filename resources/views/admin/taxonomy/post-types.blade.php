@extends('admin.layout')

@section('title', 'أنواع المقالات')
@section('topbar-title', 'أنواع المقالات')

@section('content')

<div class="admin-page-header" style="display:flex; align-items:flex-start; justify-content:space-between">
    <div>
        <h1>أنواع المقالات</h1>
        <p>إدارة أنواع المحتوى مثل: نقاش، إعلان، توثيق…</p>
    </div>
    <button onclick="document.getElementById('addTypeModal').classList.toggle('hidden')" class="btn btn-primary btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        نوع جديد
    </button>
</div>

{{-- Add form --}}
<div id="addTypeModal" class="{{ $errors->any() ? '' : 'hidden' }}" style="margin-bottom:1.5rem">
    <div class="admin-table-wrap" style="padding:1.25rem">
        <h3 style="font-size:0.9375rem; font-weight:600; margin-bottom:1rem; color:var(--color-text-primary)">إضافة نوع جديد</h3>
        <form method="POST" action="{{ route('admin.post-types.store') }}" style="display:flex; gap:0.75rem; align-items:end; flex-wrap:wrap">
            @csrf
            <div>
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">الاسم *</label>
                <input type="text" name="name" required placeholder="اسم النوع" value="{{ old('name') }}" style="background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.875rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif; min-width:200px">
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

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>Slug</th>
                <th>اللون</th>
                <th>افتراضي</th>
                <th>الترتيب</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($postTypes as $type)
            <tr>
                <td style="color:var(--color-text-muted); font-size:0.75rem">{{ $type->id }}</td>
                <td>
                    <span style="display:inline-flex; align-items:center; gap:0.4rem; font-weight:600; font-size:0.875rem; color:{{ $type->color }}">
                        <span class="color-dot" style="background:{{ $type->color }}"></span>
                        {{ $type->name }}
                    </span>
                </td>
                <td><code style="font-size:0.8rem">{{ $type->slug }}</code></td>
                <td>
                    <span style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:var(--color-text-muted)">
                        <span class="color-dot" style="background:{{ $type->color }}"></span>
                        {{ $type->color }}
                    </span>
                </td>
                <td>
                    @if($type->is_default)
                        <span class="status-badge status-published">افتراضي</span>
                    @else
                        <span style="color:var(--color-text-muted); font-size:0.8rem">—</span>
                    @endif
                </td>
                <td style="font-size:0.8125rem">{{ $type->sort_order }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.375rem">
                        {{-- Edit inline --}}
                        <button onclick="toggleTypeEdit('type-{{ $type->id }}')" class="btn btn-ghost btn-sm btn-icon" title="تعديل">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        @unless($type->is_default)
                        <form method="POST" action="{{ route('admin.post-types.destroy', $type) }}" class="action-form" onsubmit="return confirm('حذف هذا النوع؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-icon" title="حذف">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                            </button>
                        </form>
                        @endunless
                    </div>
                    {{-- Edit row --}}
                    <form method="POST" action="{{ route('admin.post-types.update', $type) }}" id="type-{{ $type->id }}" style="display:none; margin-top:0.5rem">
                        @csrf @method('PATCH')
                        <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap">
                            <input type="text" name="name" value="{{ $type->name }}" required style="background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.3rem 0.6rem; font-size:0.8rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif; min-width:120px">
                            <input type="color" name="color" value="{{ $type->color }}" style="height:32px; width:42px; background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.1rem; cursor:pointer">
                            <button type="submit" class="btn btn-primary btn-sm">حفظ</button>
                        </div>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding:2rem; color:var(--color-text-muted)">لا توجد أنواع</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function toggleTypeEdit(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' || el.style.display === '' ? 'block' : 'none';
}
</script>
@endpush

@endsection
