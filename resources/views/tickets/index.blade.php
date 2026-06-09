@extends('layouts.app')

@section('title', 'داشبورد التذاكر')

@section('content')
<div class="site-container" style="padding-top:2rem; padding-bottom:4rem">

    {{-- Page Header --}}
    <div class="page-header" style="margin-bottom:1.75rem">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem">
            <div>
                <h1 class="page-title">
                    <svg width="22" height="22" fill="none" stroke="var(--color-cyan-400)" stroke-width="2" viewBox="0 0 24 24" style="display:inline; vertical-align:-3px; margin-left:0.5rem">
                        <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                        <rect x="1" y="5" width="22" height="14" rx="3"/>
                    </svg>
                    إدارة التذاكر
                </h1>
                <p style="color:var(--color-text-muted); font-size:0.875rem; margin-top:0.25rem">
                    عرض وإدارة جميع طلبات الزيارات والدعم
                </p>
            </div>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary" target="_blank">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                تذكرة جديدة
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-ghost btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                إدارة الأنواع
            </a>
            @endif
        </div>
    </div>

    {{-- Stats Strip --}}
    <div class="stats-strip" style="margin-bottom:1.75rem; justify-content:flex-start; gap:2rem">
        <div class="stat-item">
            <span class="stat-value">{{ $stats['total'] }}</span>
            <span class="stat-label">إجمالي التذاكر</span>
        </div>
        <div style="width:1px; background:var(--color-slate-border); height:36px"></div>
        <div class="stat-item">
            <span class="stat-value" style="color:#f59e0b">{{ $stats['pending'] }}</span>
            <span class="stat-label">قيد الانتظار</span>
        </div>
        <div style="width:1px; background:var(--color-slate-border); height:36px"></div>
        <div class="stat-item">
            <span class="stat-value" style="color:#8b5cf6">{{ $stats['in_progress'] }}</span>
            <span class="stat-label">جاري التنفيذ</span>
        </div>
        <div style="width:1px; background:var(--color-slate-border); height:36px"></div>
        <div class="stat-item">
            <span class="stat-value" style="color:#10b981">{{ $stats['completed'] }}</span>
            <span class="stat-label">مكتملة</span>
        </div>
    </div>

    {{-- Filters Bar --}}
    <form method="GET" action="{{ route('tickets.index') }}"
          style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1.5rem; align-items:flex-end">

        {{-- Search --}}
        <div style="flex:1; min-width:220px; position:relative">
            <svg style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); color:var(--color-text-muted); pointer-events:none"
                 width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="search" name="q" value="{{ request('q') }}"
                   class="form-input"
                   placeholder="ابحث بالاسم، الشركة، رقم التذكرة، الهاتف…"
                   style="padding-right:2.5rem">
        </div>

        {{-- Status Filter --}}
        <select name="status" class="form-select" style="min-width:160px; width:auto">
            <option value="">كل الحالات</option>
            <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>قيد الانتظار</option>
            <option value="confirmed"   {{ request('status') === 'confirmed'   ? 'selected' : '' }}>تم التأكيد</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>جاري التنفيذ</option>
            <option value="completed"   {{ request('status') === 'completed'   ? 'selected' : '' }}>مكتمل</option>
            <option value="cancelled"   {{ request('status') === 'cancelled'   ? 'selected' : '' }}>ملغي</option>
        </select>

        {{-- Visit Type Filter --}}
        <select name="ticket_type_id" class="form-select" style="min-width:160px; width:auto">
            <option value="">كل الأنواع</option>
            @foreach($ticketTypes as $type)
                <option value="{{ $type->id }}" {{ request('ticket_type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-outline">بحث</button>
        @if(request()->hasAny(['q', 'status', 'ticket_type_id', 'visit_type']))
            <a href="{{ route('tickets.index') }}" class="btn btn-ghost" style="color:var(--color-text-muted)">مسح</a>
        @endif
    </form>

    {{-- Table --}}
    @if($tickets->isEmpty())
        <div class="card" style="padding:3rem; text-align:center; color:var(--color-text-muted)">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                 style="margin:0 auto 1rem; display:block; opacity:0.35">
                <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                <rect x="1" y="5" width="22" height="14" rx="3"/>
            </svg>
            <p style="font-size:1rem">لا توجد تذاكر تطابق البحث</p>
        </div>
    @else
        <div class="card" style="overflow:hidden">
            <div style="overflow-x:auto">
                <table style="width:100%; border-collapse:collapse; font-size:0.875rem">
                    <thead>
                        <tr style="border-bottom:1px solid var(--color-slate-border); background:var(--color-surface-900)">
                            <th style="padding:0.75rem 1rem; text-align:right; font-weight:600; color:var(--color-text-muted); white-space:nowrap">رقم التذكرة</th>
                            <th style="padding:0.75rem 1rem; text-align:right; font-weight:600; color:var(--color-text-muted)">العميل</th>
                            <th style="padding:0.75rem 1rem; text-align:right; font-weight:600; color:var(--color-text-muted); white-space:nowrap">الهاتف</th>
                            <th style="padding:0.75rem 1rem; text-align:right; font-weight:600; color:var(--color-text-muted); white-space:nowrap">نوع الزيارة</th>
                            <th style="padding:0.75rem 1rem; text-align:right; font-weight:600; color:var(--color-text-muted); white-space:nowrap">التكلفة</th>
                            <th style="padding:0.75rem 1rem; text-align:right; font-weight:600; color:var(--color-text-muted)">الحالة</th>
                            <th style="padding:0.75rem 1rem; text-align:right; font-weight:600; color:var(--color-text-muted); white-space:nowrap">تاريخ الطلب</th>
                            <th style="padding:0.75rem 1rem; text-align:center; font-weight:600; color:var(--color-text-muted)">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr style="border-bottom:1px solid rgba(30,58,82,0.4); transition:background var(--transition-fast)"
                            onmouseover="this.style.background='var(--color-surface-700)'"
                            onmouseout="this.style.background='transparent'">

                            <td style="padding:0.875rem 1rem; white-space:nowrap">
                                <span style="font-family:var(--font-mono); font-size:0.8rem; color:var(--color-cyan-400)">
                                    {{ $ticket->ticket_number }}
                                </span>
                            </td>

                            <td style="padding:0.875rem 1rem">
                                <div style="font-weight:500; color:var(--color-text-primary)">{{ $ticket->name }}</div>
                                @if($ticket->company_name)
                                    <div style="font-size:0.8rem; color:var(--color-text-muted)">{{ $ticket->company_name }}</div>
                                @endif
                            </td>

                            <td style="padding:0.875rem 1rem; white-space:nowrap; direction:ltr; text-align:right; color:var(--color-text-secondary)">
                                {{ $ticket->phone }}
                            </td>

                            <td style="padding:0.875rem 1rem; white-space:nowrap; color:var(--color-text-secondary)">
                                {{ $ticket->ticketType?->name ?? $ticket->visit_type_label }}
                            </td>

                            <td style="padding:0.875rem 1rem; white-space:nowrap">
                                @if($ticket->expected_cost)
                                    <span style="color:var(--color-success); font-weight:500">
                                        {{ number_format($ticket->expected_cost, 0) }} ج.م
                                    </span>
                                @else
                                    <span style="color:var(--color-text-muted)">—</span>
                                @endif
                            </td>

                            <td style="padding:0.875rem 1rem; white-space:nowrap">
                                <span style="display:inline-flex; align-items:center; gap:0.35rem;
                                             padding:0.2rem 0.65rem; border-radius:999px; font-size:0.8rem; font-weight:500;
                                             background:{{ $ticket->status_color }}18;
                                             color:{{ $ticket->status_color }};
                                             border:1px solid {{ $ticket->status_color }}33">
                                    <span style="width:6px; height:6px; border-radius:50%; background:{{ $ticket->status_color }}; flex-shrink:0"></span>
                                    {{ $ticket->status_label }}
                                </span>
                            </td>

                            <td style="padding:0.875rem 1rem; white-space:nowrap; color:var(--color-text-muted); font-size:0.8rem">
                                {{ $ticket->created_at->format('Y/m/d') }}<br>
                                <span style="font-size:0.75rem">{{ $ticket->created_at->format('H:i') }}</span>
                            </td>

                            <td style="padding:0.875rem 1rem; text-align:center">
                                <a href="{{ route('tickets.show', $ticket) }}"
                                   class="btn btn-ghost btn-sm"
                                   style="padding:0.3rem 0.75rem; font-size:0.8rem">
                                    عرض
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($tickets->hasPages())
            <div style="margin-top:1.5rem; display:flex; justify-content:center">
                {{ $tickets->links('components.pagination') }}
            </div>
        @endif
    @endif

</div>
@endsection
