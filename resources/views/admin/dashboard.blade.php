@extends('admin.layout')

@section('title', 'نظرة عامة')
@section('topbar-title', 'نظرة عامة')

@section('content')

<div class="admin-page-header">
    <h1>مرحباً، {{ auth()->user()->display_name }} 👋</h1>
    <p>إليك نظرة عامة على نشاط الموقع</p>
</div>

{{-- ── Stats Grid ─────────────────────────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,211,238,0.12)">
            <svg width="20" height="20" fill="none" stroke="#22d3ee" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="stat-value">{{ number_format($stats['users']) }}</div>
        <div class="stat-label">المستخدمون</div>
        <div style="font-size:0.75rem; color:var(--color-success); margin-top:0.25rem">+{{ $newUsersThisMonth }} هذا الشهر</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(139,92,246,0.12)">
            <svg width="20" height="20" fill="none" stroke="#8b5cf6" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="stat-value">{{ number_format($stats['published']) }}</div>
        <div class="stat-label">مقالات منشورة</div>
        <div style="font-size:0.75rem; color:var(--color-success); margin-top:0.25rem">+{{ $newPostsThisMonth }} هذا الشهر</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,0.12)">
            <svg width="20" height="20" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($stats['drafts']) }}</div>
        <div class="stat-label">مسودات</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(16,185,129,0.12)">
            <svg width="20" height="20" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($stats['comments']) }}</div>
        <div class="stat-label">التعليقات</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,0.1)">
            <svg width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($stats['votes']) }}</div>
        <div class="stat-label">الأصوات</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,211,238,0.08)">
            <svg width="20" height="20" fill="none" stroke="#22d3ee" stroke-width="2" viewBox="0 0 24 24"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($stats['bookmarks']) }}</div>
        <div class="stat-label">المحفوظات</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,0.1)">
            <svg width="20" height="20" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($stats['categories']) }}</div>
        <div class="stat-label">التصنيفات</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(139,92,246,0.1)">
            <svg width="20" height="20" fill="none" stroke="#8b5cf6" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        </div>
        <div class="stat-value">{{ number_format($stats['tags']) }}</div>
        <div class="stat-label">الوسوم</div>
    </div>
</div>

{{-- ── Tickets Section ─────────────────────────────────────────────────── --}}
<div style="margin-bottom:2rem">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.875rem">
        <h2 style="font-size:1rem; font-weight:700; color:var(--color-text-primary); display:flex; align-items:center; gap:0.5rem">
            <svg width="17" height="17" fill="none" stroke="var(--color-cyan-400)" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                <rect x="1" y="5" width="22" height="14" rx="3"/>
            </svg>
            تذاكر الحجز
        </h2>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-ghost btn-sm">عرض الكل</a>
    </div>

    {{-- Ticket Stats --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:0.875rem; margin-bottom:1rem">
        <div style="background:var(--color-surface-800); border:1px solid var(--color-slate-border); border-radius:var(--radius-card); padding:1rem; display:flex; align-items:center; gap:0.75rem">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(34,211,238,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="17" height="17" fill="none" stroke="#22d3ee" stroke-width="2" viewBox="0 0 24 24"><path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/><rect x="1" y="5" width="22" height="14" rx="3"/></svg>
            </div>
            <div>
                <div style="font-size:1.375rem;font-weight:700;color:var(--color-text-primary);line-height:1">{{ $stats['tickets'] }}</div>
                <div style="font-size:0.75rem;color:var(--color-text-muted)">إجمالي التذاكر</div>
            </div>
        </div>
        <div style="background:var(--color-surface-800); border:1px solid rgba(245,158,11,0.25); border-radius:var(--radius-card); padding:1rem; display:flex; align-items:center; gap:0.75rem">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(245,158,11,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="17" height="17" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div style="font-size:1.375rem;font-weight:700;color:#f59e0b;line-height:1">{{ $stats['tickets_pending'] }}</div>
                <div style="font-size:0.75rem;color:var(--color-text-muted)">قيد الانتظار</div>
            </div>
        </div>
        <div style="background:var(--color-surface-800); border:1px solid rgba(34,211,238,0.15); border-radius:var(--radius-card); padding:1rem; display:flex; align-items:center; gap:0.75rem">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(34,211,238,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="17" height="17" fill="none" stroke="#22d3ee" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
                <div style="font-size:1.375rem;font-weight:700;color:var(--color-cyan-400);line-height:1">{{ $stats['tickets_today'] }}</div>
                <div style="font-size:0.75rem;color:var(--color-text-muted)">اليوم</div>
            </div>
        </div>
        <div style="background:var(--color-surface-800); border:1px solid rgba(16,185,129,0.2); border-radius:var(--radius-card); padding:1rem; display:flex; align-items:center; gap:0.75rem">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="17" height="17" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
                <div style="font-size:1.375rem;font-weight:700;color:#10b981;line-height:1">{{ $stats['tickets_done'] }}</div>
                <div style="font-size:0.75rem;color:var(--color-text-muted)">مكتملة</div>
            </div>
        </div>
    </div>

    {{-- Recent Tickets Table --}}
    @if($recentTickets->isNotEmpty())
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>رقم التذكرة</th>
                    <th>العميل</th>
                    <th>الهاتف</th>
                    <th>نوع الزيارة</th>
                    <th>التكلفة</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTickets as $ticket)
                <tr>
                    <td>
                        <span style="font-family:var(--font-mono);font-size:0.8rem;color:var(--color-cyan-400)">
                            {{ $ticket->ticket_number }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:500;color:var(--color-text-primary)">{{ $ticket->name }}</div>
                        @if($ticket->company_name)
                            <div style="font-size:0.75rem;color:var(--color-text-muted)">{{ $ticket->company_name }}</div>
                        @endif
                    </td>
                    <td style="direction:ltr;text-align:right;font-size:0.8125rem">{{ $ticket->phone }}</td>
                    <td style="font-size:0.8125rem">{{ $ticket->ticketType?->name ?? $ticket->visit_type_label }}</td>
                    <td>
                        @if($ticket->expected_cost)
                            <span style="color:var(--color-success);font-weight:500;font-size:0.8125rem">
                                {{ number_format($ticket->expected_cost, 0) }} ج.م
                            </span>
                        @else
                            <span style="color:var(--color-text-muted)">—</span>
                        @endif
                    </td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:0.3rem;
                                     padding:0.15rem 0.55rem;border-radius:999px;font-size:0.75rem;font-weight:600;
                                     background:{{ $ticket->status_color }}18;color:{{ $ticket->status_color }};
                                     border:1px solid {{ $ticket->status_color }}33">
                            <span style="width:5px;height:5px;border-radius:50%;background:{{ $ticket->status_color }}"></span>
                            {{ $ticket->status_label }}
                        </span>
                    </td>
                    <td style="font-size:0.75rem;white-space:nowrap">{{ $ticket->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-ghost btn-sm btn-icon" title="عرض التذكرة">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="background:var(--color-surface-800);border:1px solid var(--color-slate-border);border-radius:var(--radius-card);padding:2rem;text-align:center;color:var(--color-text-muted);font-size:0.875rem">
        لا توجد تذاكر حتى الآن —
        <a href="{{ route('tickets.create') }}" target="_blank" style="color:var(--color-cyan-400)">جرب إنشاء تذكرة</a>
    </div>
    @endif
</div>

{{-- ── Two-column layout ───────────────────────────────────────────────── --}}
<div style="display:grid; grid-template-columns:1fr 380px; gap:1.5rem; margin-bottom:1.5rem">

    {{-- Recent Posts --}}
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem">
            <h2 style="font-size:1rem; font-weight:600; color:var(--color-text-primary)">أحدث المقالات</h2>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost btn-sm">عرض الكل</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الكاتب</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPosts as $post)
                    <tr>
                        <td style="max-width:200px">
                            <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--color-text-primary); font-weight:500">
                                {{ $post->title }}
                            </div>
                        </td>
                        <td>
                            <span style="color:var(--color-text-secondary)">{{ $post->author->display_name }}</span>
                        </td>
                        <td>
                            @if($post->trashed())
                                <span class="status-badge status-trashed">محذوف</span>
                            @else
                                <span class="status-badge status-{{ $post->status }}">
                                    {{ match($post->status) { 'published' => 'منشور', 'draft' => 'مسودة', 'archived' => 'مؤرشف', default => $post->status } }}
                                </span>
                            @endif
                        </td>
                        <td style="font-size:0.75rem; white-space:nowrap">{{ $post->created_at->diffForHumans() }}</td>
                        <td>
                            @unless($post->trashed())
                            <a href="{{ route('posts.show', $post) }}" target="_blank" class="btn btn-ghost btn-sm btn-icon" title="عرض">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                            @endunless
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Users --}}
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem">
            <h2 style="font-size:1rem; font-weight:600; color:var(--color-text-primary)">أحدث المستخدمين</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">عرض الكل</a>
        </div>
        <div class="admin-table-wrap">
            @foreach($recentUsers as $user)
            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; border-bottom:1px solid rgba(30,58,82,0.5)">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:34px;height:34px;border-radius:50%;flex-shrink:0">
                <div style="flex:1; min-width:0">
                    <div style="font-size:0.875rem; font-weight:500; color:var(--color-text-primary); overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $user->display_name }}</div>
                    <div style="font-size:0.75rem; color:var(--color-text-muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $user->email }}</div>
                </div>
                <span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Bottom row ──────────────────────────────────────────────────────── --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem">

    {{-- Top Posts --}}
    <div>
        <h2 style="font-size:1rem; font-weight:600; color:var(--color-text-primary); margin-bottom:0.75rem">الأكثر مشاهدة</h2>
        <div class="admin-table-wrap">
            @foreach($topPosts as $i => $post)
            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; border-bottom:1px solid rgba(30,58,82,0.5)">
                <div style="width:24px; height:24px; border-radius:6px; background:rgba(34,211,238,0.1); display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:var(--color-cyan-400); flex-shrink:0">{{ $i + 1 }}</div>
                <div style="flex:1; min-width:0">
                    <a href="{{ route('posts.show', $post) }}" target="_blank" style="font-size:0.875rem; font-weight:500; color:var(--color-text-primary); display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $post->title }}</a>
                </div>
                <div style="font-size:0.75rem; color:var(--color-text-muted); white-space:nowrap">{{ number_format($post->views_count) }} مشاهدة</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Comments --}}
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem">
            <h2 style="font-size:1rem; font-weight:600; color:var(--color-text-primary)">أحدث التعليقات</h2>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-ghost btn-sm">عرض الكل</a>
        </div>
        <div class="admin-table-wrap">
            @foreach($recentComments as $comment)
            <div style="padding:0.75rem 1rem; border-bottom:1px solid rgba(30,58,82,0.5)">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.3rem">
                    <img src="{{ $comment->author?->avatar_url }}" alt="" style="width:22px;height:22px;border-radius:50%">
                    <span style="font-size:0.8rem; font-weight:500; color:var(--color-text-secondary)">{{ $comment->author?->display_name }}</span>
                    @if($comment->trashed())
                        <span class="status-badge status-trashed" style="font-size:0.65rem">محذوف</span>
                    @endif
                    <span style="font-size:0.7rem; color:var(--color-text-muted); margin-right:auto">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div style="font-size:0.8125rem; color:var(--color-text-muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $comment->body }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
