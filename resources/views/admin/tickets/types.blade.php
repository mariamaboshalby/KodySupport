@extends('admin.layout')

@section('title', 'أنواع التذاكر')
@section('topbar-title', 'أنواع التذاكر')

@section('content')

<div class="admin-page-header admin-page-header-actions">
    <div>
        <h1>أنواع التذاكر</h1>
        <p>إدارة أنواع حجوزات الدعم مع التكلفة المتوقعة لكل نوع</p>
    </div>
    <div style="display:flex; gap:0.5rem; align-items:center">
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-ghost btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/><rect x="1" y="5" width="22" height="14" rx="3"/></svg>
            التذاكر
        </a>
        <button onclick="document.getElementById('addTypeModal').classList.toggle('hidden')" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            نوع جديد
        </button>
    </div>
</div>

{{-- ── نموذج الإضافة ──────────────────────────────────────────────────────── --}}
<div id="addTypeModal" class="{{ $errors->any() ? '' : 'hidden' }}" style="margin-bottom:1.5rem">
    <div class="admin-table-wrap" style="padding:1.25rem">
        <h3 style="font-size:0.9375rem; font-weight:600; margin-bottom:1rem; color:var(--color-text-primary)">إضافة نوع جديد</h3>
        <form method="POST" action="{{ route('admin.ticket-types.store') }}"
              style="display:flex; gap:0.75rem; align-items:end; flex-wrap:wrap">
            @csrf

            <div>
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">اسم النوع *</label>
                <input type="text" name="name" required placeholder="مثال: صيانة طارئة"
                       value="{{ old('name') }}"
                       style="background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem;
                              padding:0.5rem 0.75rem; font-size:0.875rem; color:var(--color-text-primary);
                              font-family:'Cairo',sans-serif; min-width:200px">
                @error('name')
                    <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.25rem">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">التكلفة المتوقعة (ج.م)</label>
                <input type="number" name="expected_cost" min="0" step="0.01"
                       placeholder="0.00"
                       value="{{ old('expected_cost') }}"
                       style="background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem;
                              padding:0.5rem 0.75rem; font-size:0.875rem; color:var(--color-text-primary);
                              font-family:'Cairo',sans-serif; width:160px">
                @error('expected_cost')
                    <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.25rem">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="font-size:0.75rem; color:var(--color-text-muted); display:block; margin-bottom:0.25rem">الترتيب</label>
                <input type="number" name="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                       style="background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.5rem;
                              padding:0.5rem 0.75rem; font-size:0.875rem; color:var(--color-text-primary);
                              font-family:'Cairo',sans-serif; width:90px">
            </div>

            <button type="submit" class="btn btn-primary btn-sm" style="align-self:end">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                حفظ
            </button>
        </form>
    </div>
</div>

{{-- ── جدول الأنواع ────────────────────────────────────────────────────────── --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-hide-mobile" style="width:50px">#</th>
                <th>اسم النوع</th>
                <th class="col-hide-mobile">Slug</th>
                <th>التكلفة المتوقعة</th>
                <th class="col-hide-mobile">الترتيب</th>
                <th>الحالة</th>
                <th class="col-hide-mobile">التذاكر</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ticketTypes as $type)
            <tr>
                <td class="col-hide-mobile" style="color:var(--color-text-muted); font-size:0.75rem">{{ $type->id }}</td>

                <td style="font-weight:600; color:var(--color-text-primary)">{{ $type->name }}</td>

                <td class="col-hide-mobile"><code style="font-size:0.8rem; color:var(--color-text-muted)">{{ $type->slug }}</code></td>

                <td>
                    @if($type->expected_cost !== null)
                        <span style="font-weight:600; color:#22d3ee">
                            {{ number_format($type->expected_cost, 2) }}
                            <span style="font-size:0.75rem; color:var(--color-text-muted)">ج.م</span>
                        </span>
                    @else
                        <span style="color:var(--color-text-muted); font-size:0.8rem">—</span>
                    @endif
                </td>

                <td class="col-hide-mobile" style="font-size:0.8125rem; color:var(--color-text-muted)">{{ $type->sort_order }}</td>

                <td>
                    @if($type->is_active)
                        <span class="status-badge status-published">مفعّل</span>
                    @else
                        <span class="status-badge status-archived">معطّل</span>
                    @endif
                </td>

                <td class="col-hide-mobile" style="font-size:0.8125rem; color:var(--color-text-muted)">
                    {{ $type->tickets_count ?? $type->tickets()->count() }}
                </td>

                <td>
                    <div style="display:flex; align-items:center; gap:0.375rem">
                        <button onclick="toggleTypeEdit('edit-{{ $type->id }}')"
                                class="btn btn-ghost btn-sm btn-icon" title="تعديل">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>

                        <form method="POST" action="{{ route('admin.ticket-types.destroy', $type) }}"
                              class="action-form"
                              onsubmit="return confirm('حذف هذا النوع نهائيًا؟ لا يمكن حذفه إذا كان مرتبطًا بتذاكر.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-icon" title="حذف">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- نموذج التعديل المضمّن --}}
                    <form method="POST" action="{{ route('admin.ticket-types.update', $type) }}"
                          id="edit-{{ $type->id }}"
                          style="display:none; margin-top:0.75rem; background:var(--color-surface-700);
                                 border:1px solid var(--color-slate-border); border-radius:0.5rem; padding:0.75rem">
                        @csrf @method('PATCH')

                        <div style="display:flex; gap:0.5rem; align-items:end; flex-wrap:wrap">
                            <div>
                                <label style="font-size:0.7rem; color:var(--color-text-muted); display:block; margin-bottom:0.2rem">الاسم</label>
                                <input type="text" name="name" value="{{ $type->name }}" required
                                       style="background:var(--color-surface-800); border:1px solid var(--color-slate-border);
                                              border-radius:0.375rem; padding:0.35rem 0.6rem; font-size:0.8rem;
                                              color:var(--color-text-primary); font-family:'Cairo',sans-serif; min-width:140px">
                            </div>

                            <div>
                                <label style="font-size:0.7rem; color:var(--color-text-muted); display:block; margin-bottom:0.2rem">التكلفة (ج.م)</label>
                                <input type="number" name="expected_cost" min="0" step="0.01"
                                       value="{{ $type->expected_cost }}"
                                       placeholder="0.00"
                                       style="background:var(--color-surface-800); border:1px solid var(--color-slate-border);
                                              border-radius:0.375rem; padding:0.35rem 0.6rem; font-size:0.8rem;
                                              color:var(--color-text-primary); font-family:'Cairo',sans-serif; width:120px">
                            </div>

                            <div>
                                <label style="font-size:0.7rem; color:var(--color-text-muted); display:block; margin-bottom:0.2rem">الترتيب</label>
                                <input type="number" name="sort_order" min="0"
                                       value="{{ $type->sort_order }}"
                                       style="background:var(--color-surface-800); border:1px solid var(--color-slate-border);
                                              border-radius:0.375rem; padding:0.35rem 0.6rem; font-size:0.8rem;
                                              color:var(--color-text-primary); font-family:'Cairo',sans-serif; width:80px">
                            </div>

                            <div style="display:flex; align-items:center; gap:0.4rem; padding-bottom:0.4rem">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" id="active-{{ $type->id }}"
                                       {{ $type->is_active ? 'checked' : '' }}
                                       style="accent-color:var(--color-cyan-400)">
                                <label for="active-{{ $type->id }}"
                                       style="font-size:0.8rem; color:var(--color-text-secondary); cursor:pointer">مفعّل</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm">حفظ</button>
                            <button type="button" onclick="toggleTypeEdit('edit-{{ $type->id }}')"
                                    class="btn btn-ghost btn-sm">إلغاء</button>
                        </div>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:3rem; color:var(--color-text-muted)">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5"
                         viewBox="0 0 24 24" style="margin:0 auto 0.75rem; display:block; opacity:0.4">
                        <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                        <rect x="1" y="5" width="22" height="14" rx="3"/>
                    </svg>
                    لا توجد أنواع تذاكر بعد — أضف النوع الأول
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function toggleTypeEdit(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}
</script>
@endpush

@endsection
