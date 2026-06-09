@extends('layouts.app')

@section('title', 'تم الحجز بنجاح — ' . $ticket->ticket_number)

@section('content')
<div class="site-container" style="max-width:580px; padding-top:3rem; padding-bottom:4rem; text-align:center">

    {{-- Success Icon --}}
    <div style="display:inline-flex; align-items:center; justify-content:center;
                width:72px; height:72px; border-radius:50%;
                background:rgba(16,185,129,0.15); border:2px solid rgba(16,185,129,0.4);
                margin-bottom:1.5rem">
        <svg width="36" height="36" fill="none" stroke="#10b981" stroke-width="2.5" viewBox="0 0 24 24">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
    </div>

    <h1 style="font-size:1.875rem; font-weight:700; color:var(--color-text-primary); margin-bottom:0.75rem">
        تم الحجز بنجاح!
    </h1>
    <p style="color:var(--color-text-secondary); font-size:0.9375rem; margin-bottom:2rem; line-height:1.7">
        شكراً <strong style="color:var(--color-text-primary)">{{ $ticket->name }}</strong>،
        استلمنا طلبك وسيتواصل معك فريقنا لتأكيد الموعد في أقرب وقت.
    </p>

    {{-- Ticket Details Card --}}
    <div class="card" style="padding:1.75rem; text-align:right; margin-bottom:1.5rem">

        {{-- Ticket Number Badge --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;
                    padding-bottom:1rem; border-bottom:1px solid var(--color-slate-border)">
            <span style="font-size:0.8125rem; color:var(--color-text-muted); font-weight:600; letter-spacing:0.05em; text-transform:uppercase">
                رقم التذكرة
            </span>
            <span style="font-family:var(--font-mono); font-size:1rem; font-weight:700; color:var(--color-cyan-400);
                         background:rgba(34,211,238,0.1); padding:0.3rem 0.8rem; border-radius:6px;
                         border:1px solid rgba(34,211,238,0.25)">
                {{ $ticket->ticket_number }}
            </span>
        </div>

        {{-- Details Grid --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem">

            <div>
                <div style="font-size:0.75rem; color:var(--color-text-muted); margin-bottom:0.2rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em">الاسم</div>
                <div style="font-size:0.9375rem; color:var(--color-text-primary)">{{ $ticket->name }}</div>
            </div>

            @if($ticket->company_name)
            <div>
                <div style="font-size:0.75rem; color:var(--color-text-muted); margin-bottom:0.2rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em">الشركة</div>
                <div style="font-size:0.9375rem; color:var(--color-text-primary)">{{ $ticket->company_name }}</div>
            </div>
            @endif

            <div>
                <div style="font-size:0.75rem; color:var(--color-text-muted); margin-bottom:0.2rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em">الهاتف</div>
                <div style="font-size:0.9375rem; color:var(--color-text-primary); direction:ltr; text-align:right">{{ $ticket->phone }}</div>
            </div>

            <div>
                <div style="font-size:0.75rem; color:var(--color-text-muted); margin-bottom:0.2rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em">نوع الزيارة</div>
                <div style="font-size:0.9375rem; color:var(--color-text-primary)">{{ $ticket->visit_type_label }}</div>
            </div>

            @if($ticket->address)
            <div style="grid-column:1/-1">
                <div style="font-size:0.75rem; color:var(--color-text-muted); margin-bottom:0.2rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em">العنوان</div>
                <div style="font-size:0.9375rem; color:var(--color-text-primary)">{{ $ticket->address }}</div>
            </div>
            @endif

            @if($ticket->expected_cost)
            <div>
                <div style="font-size:0.75rem; color:var(--color-text-muted); margin-bottom:0.2rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em">التكلفة المتوقعة</div>
                <div style="font-size:0.9375rem; color:var(--color-success); font-weight:600">
                    {{ number_format($ticket->expected_cost, 2) }} ج.م
                </div>
            </div>
            @endif

            <div>
                <div style="font-size:0.75rem; color:var(--color-text-muted); margin-bottom:0.2rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em">الحالة</div>
                <div style="display:inline-flex; align-items:center; gap:0.4rem">
                    <span style="width:8px; height:8px; border-radius:50%; background:{{ $ticket->status_color }}; display:inline-block; flex-shrink:0"></span>
                    <span style="font-size:0.9375rem; color:var(--color-text-primary)">{{ $ticket->status_label }}</span>
                </div>
            </div>

        </div>
    </div>

    {{-- Info Box --}}
    <div class="alert alert-info" style="text-align:right; margin-bottom:2rem">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
        </svg>
        <span>احتفظ برقم التذكرة <strong>{{ $ticket->ticket_number }}</strong> لمتابعة حالة طلبك.</span>
    </div>

    {{-- CTA Buttons --}}
    <div style="display:flex; gap:0.75rem; justify-content:center; flex-wrap:wrap">
        <a href="{{ route('tickets.create') }}" class="btn btn-outline">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            حجز تذكرة جديدة
        </a>
        <a href="{{ route('home') }}" class="btn btn-ghost">
            العودة للرئيسية
        </a>
    </div>

</div>
@endsection
