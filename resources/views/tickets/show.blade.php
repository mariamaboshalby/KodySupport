@extends('layouts.app')

@section('title', 'تذكرة ' . $ticket->ticket_number)

@section('content')
<div class="site-container" style="max-width:900px; padding-top:2rem; padding-bottom:4rem">

    {{-- Breadcrumb --}}
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.8125rem; color:var(--color-text-muted); margin-bottom:1.75rem">
        <a href="{{ route('tickets.index') }}" style="color:var(--color-text-muted)">التذاكر</a>
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        <span style="font-family:var(--font-mono); color:var(--color-cyan-400)">{{ $ticket->ticket_number }}</span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start">

        {{-- Main Info --}}
        <div>
            {{-- Header Card --}}
            <div class="card" style="padding:1.75rem; margin-bottom:1.25rem">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap">
                    <div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted); letter-spacing:0.06em; text-transform:uppercase; font-weight:600; margin-bottom:0.5rem">
                            رقم التذكرة
                        </div>
                        <div style="font-family:var(--font-mono); font-size:1.25rem; font-weight:700; color:var(--color-cyan-400)">
                            {{ $ticket->ticket_number }}
                        </div>
                    </div>
                    <span style="display:inline-flex; align-items:center; gap:0.4rem;
                                 padding:0.35rem 0.9rem; border-radius:999px; font-size:0.875rem; font-weight:600;
                                 background:{{ $ticket->status_color }}18; color:{{ $ticket->status_color }};
                                 border:1px solid {{ $ticket->status_color }}40">
                        <span style="width:8px; height:8px; border-radius:50%; background:{{ $ticket->status_color }}"></span>
                        {{ $ticket->status_label }}
                    </span>
                </div>

                <hr style="border:none; border-top:1px solid var(--color-slate-border); margin:1.25rem 0">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem">

                    <div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.3rem">الاسم</div>
                        <div style="color:var(--color-text-primary); font-size:0.9375rem; font-weight:500">{{ $ticket->name }}</div>
                    </div>

                    @if($ticket->company_name)
                    <div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.3rem">الشركة</div>
                        <div style="color:var(--color-text-primary); font-size:0.9375rem">{{ $ticket->company_name }}</div>
                    </div>
                    @endif

                    <div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.3rem">الهاتف</div>
                        <div style="color:var(--color-text-primary); font-size:0.9375rem; direction:ltr; text-align:right">{{ $ticket->phone }}</div>
                    </div>

                    <div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.3rem">نوع الزيارة</div>
                        <div style="color:var(--color-text-primary); font-size:0.9375rem">{{ $ticket->visit_type_label }}</div>
                    </div>

                    @if($ticket->address)
                    <div style="grid-column:1/-1">
                        <div style="font-size:0.75rem; color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.3rem">العنوان</div>
                        <div style="color:var(--color-text-primary); font-size:0.9375rem">{{ $ticket->address }}</div>
                    </div>
                    @endif

                    @if($ticket->expected_cost)
                    <div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.3rem">التكلفة المتوقعة</div>
                        <div style="color:var(--color-success); font-size:1.0625rem; font-weight:700">
                            {{ number_format($ticket->expected_cost, 2) }} ج.م
                        </div>
                    </div>
                    @endif

                    <div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.3rem">تاريخ الطلب</div>
                        <div style="color:var(--color-text-secondary); font-size:0.9rem">
                            {{ $ticket->created_at->format('d/m/Y — H:i') }}
                        </div>
                    </div>

                </div>

                @if($ticket->notes)
                    <hr style="border:none; border-top:1px solid var(--color-slate-border); margin:1.25rem 0">
                    <div>
                        <div style="font-size:0.75rem; color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.5rem">ملاحظات العميل</div>
                        <div style="color:var(--color-text-secondary); font-size:0.9375rem; line-height:1.7;
                                    background:var(--color-surface-700); padding:1rem; border-radius:8px;
                                    border:1px solid var(--color-slate-border)">
                            {{ $ticket->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar: Update Status --}}
        <div>
            <div class="card" style="padding:1.5rem">
                <div style="font-size:0.8125rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase;
                             color:var(--color-text-muted); margin-bottom:1.25rem; padding-bottom:0.75rem;
                             border-bottom:1px solid var(--color-slate-border)">
                    تحديث التذكرة
                </div>

                @if(session('success'))
                    <div class="alert alert-success" style="margin-bottom:1rem; padding:0.625rem 0.875rem; font-size:0.8125rem">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label class="form-label" for="status">الحالة</label>
                        <select id="status" name="status" class="form-select">
                            <option value="pending"     {{ $ticket->status === 'pending'     ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="confirmed"   {{ $ticket->status === 'confirmed'   ? 'selected' : '' }}>تم التأكيد</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>جاري التنفيذ</option>
                            <option value="completed"   {{ $ticket->status === 'completed'   ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled"   {{ $ticket->status === 'cancelled'   ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="scheduled_at">موعد الزيارة</label>
                        <input
                            id="scheduled_at" name="scheduled_at" type="datetime-local"
                            class="form-input"
                            value="{{ $ticket->scheduled_at ? $ticket->scheduled_at->format('Y-m-d\TH:i') : '' }}"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notes_update">ملاحظات داخلية</label>
                        <textarea
                            id="notes_update" name="notes"
                            class="form-textarea"
                            rows="3"
                            style="min-height:80px"
                            placeholder="ملاحظات للفريق الداخلي…"
                        >{{ $ticket->notes }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        حفظ التغييرات
                    </button>
                </form>

                <hr style="border:none; border-top:1px solid var(--color-slate-border); margin:1.25rem 0">

                <a href="{{ route('tickets.index') }}" class="btn btn-ghost" style="width:100%; justify-content:center">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    رجوع للقائمة
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
