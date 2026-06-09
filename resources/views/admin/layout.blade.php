<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Cairo', sans-serif; }

        /* Admin sidebar layout */
        .admin-layout { display: flex; min-height: 100vh; }

        .admin-sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--color-surface-900);
            border-left: 1px solid var(--color-slate-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; right: 0; bottom: 0;
            z-index: 50;
            overflow-y: auto;
        }

        .admin-main {
            flex: 1;
            margin-right: 240px;
            min-height: 100vh;
            background: var(--color-navy-950);
        }

        .admin-topbar {
            background: rgba(4,15,31,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--color-slate-border);
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .admin-content {
            padding: 2rem 1.5rem;
            max-width: 1200px;
        }

        /* Sidebar nav */
        .sidebar-brand {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid var(--color-slate-border);
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .sidebar-brand-icon {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-nav { padding: 0.75rem 0.5rem; flex: 1; }

        .nav-group-label {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.5rem 0.625rem 0.25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.625rem;
            border-radius: 0.5rem;
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 150ms;
            margin-bottom: 2px;
        }
        .nav-item:hover {
            background: var(--color-surface-700);
            color: var(--color-text-primary);
        }
        .nav-item.active {
            background: rgba(34,211,238,0.1);
            color: var(--color-cyan-400);
            border: 1px solid rgba(34,211,238,0.2);
        }
        .nav-item svg { flex-shrink: 0; }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--color-slate-border);
        }

        /* Stat cards */
        .stat-card {
            background: var(--color-surface-800);
            border: 1px solid var(--color-slate-border);
            border-radius: var(--radius-card);
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-text-primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
        }

        /* Tables */
        .admin-table-wrap {
            background: var(--color-surface-800);
            border: 1px solid var(--color-slate-border);
            border-radius: var(--radius-card);
            overflow: hidden;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .admin-table thead th {
            background: var(--color-surface-900);
            padding: 0.75rem 1rem;
            text-align: right;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--color-slate-border);
        }

        .admin-table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(30,58,82,0.5);
            color: var(--color-text-secondary);
            vertical-align: middle;
        }

        .admin-table tbody tr:last-child td { border-bottom: none; }
        .admin-table tbody tr:hover td { background: rgba(17,29,46,0.6); }

        /* Role badges */
        .role-badge {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .role-admin    { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .role-moderator{ background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .role-user     { background: rgba(34,211,238,0.1);  color: var(--color-cyan-400); border: 1px solid rgba(34,211,238,0.2); }

        .status-badge {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .status-published { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .status-draft     { background: rgba(74,107,133,0.2);  color: #7ba4c4; border: 1px solid rgba(74,107,133,0.3); }
        .status-archived  { background: rgba(107,114,128,0.15); color: #9ca3af; border: 1px solid rgba(107,114,128,0.3); }
        .status-trashed   { background: rgba(239,68,68,0.1);  color: #f87171; border: 1px solid rgba(239,68,68,0.2); }

        /* Admin page header */
        .admin-page-header {
            margin-bottom: 1.75rem;
        }
        .admin-page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text-primary);
        }
        .admin-page-header p {
            font-size: 0.875rem;
            color: var(--color-text-muted);
            margin-top: 0.25rem;
        }

        /* ── Grid helpers ──────────────────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        /* Dashboard two-column layout */
        .admin-grid-2col {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 1.5rem;
        }

        /* Color dot */
        .color-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; flex-shrink: 0; }

        /* ── Filter bar ─────────────────────────────────────────────────── */
        .filter-bar {
            background: var(--color-surface-800);
            border: 1px solid var(--color-slate-border);
            border-radius: var(--radius-card);
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-bar input, .filter-bar select {
            background: var(--color-surface-700);
            border: 1px solid var(--color-slate-border);
            border-radius: 0.5rem;
            padding: 0.4rem 0.75rem;
            font-size: 0.875rem;
            color: var(--color-text-primary);
            font-family: 'Cairo', sans-serif;
        }
        .filter-bar input:focus, .filter-bar select:focus {
            outline: none;
            border-color: var(--color-cyan-500);
            box-shadow: 0 0 0 2px rgba(6,182,212,0.1);
        }
        .filter-bar input::placeholder { color: var(--color-text-muted); }
        .filter-bar select option { background: var(--color-surface-800); }

        /* Inline form (for actions in tables) */
        .action-form { display: inline; }

        /* ── Page header flex on mobile ─────────────────────────────────── */
        .admin-page-header-actions {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        /* ── Responsive ─────────────────────────────────────────────────── */

        /* Tablet: collapse dashboard grid */
        @media (max-width: 960px) {
            .admin-grid-2col { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* Mobile (≤ 768px) */
        @media (max-width: 768px) {

            /* Sidebar: slide off-screen, toggled by JS */
            .admin-sidebar {
                transform: translateX(100%);
                transition: transform 0.25s ease;
            }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-right: 0; }

            /* Dark overlay behind sidebar */
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.6);
                z-index: 45;
            }
            .sidebar-overlay.open { display: block; }

            /* Show hamburger button */
            .mobile-menu-btn { display: flex !important; }

            /* Content padding */
            .admin-content { padding: 1rem 0.875rem; }
            .admin-topbar  { padding: 0 1rem; }

            /* Stat cards: 2 columns */
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 0.625rem; }

            /* Tables: horizontal scroll */
            .admin-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

            /* Hide less important table columns */
            .admin-table .col-hide-mobile { display: none; }

            /* Filter bar stacks vertically */
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            .filter-bar input,
            .filter-bar select { width: 100%; }

            /* Page header: stack title + button */
            .admin-page-header-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            /* Dashboard ticket stats: 2 cols */
            .ticket-stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        /* Very small screens */
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .admin-content { padding: 0.75rem 0.625rem; }

            /* Topbar: hide user name on tiny screens */
            .topbar-username { display: none; }
        }

        @media (min-width: 769px) {
            .mobile-menu-btn { display: none !important; }
        }
    </style>
</head>
<body>
<div class="admin-layout">

    {{-- Mobile sidebar overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleAdminSidebar()"></div>

    {{-- ── Sidebar ──────────────────────────────────────────────────────── --}}
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>
                </svg>
            </div>
            <div>
                <div style="font-weight:700; font-size:0.9rem; color:var(--color-text-primary)">لوحة التحكم</div>
                <div style="font-size:0.7rem; color:var(--color-text-muted)">{{ config('app.name') }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group-label">عام</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                نظرة عامة
            </a>

            <div class="nav-group-label" style="margin-top:0.75rem">المحتوى</div>
            <a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                المقالات
            </a>
            <a href="{{ route('admin.comments.index') }}" class="nav-item {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                التعليقات
            </a>

            <div class="nav-group-label" style="margin-top:0.75rem">التصنيفات</div>
            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                التصنيفات
            </a>
            <a href="{{ route('admin.tags.index') }}" class="nav-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                الوسوم
            </a>
            <a href="{{ route('admin.post-types.index') }}" class="nav-item {{ request()->routeIs('admin.post-types.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                أنواع المقالات
            </a>

            <div class="nav-group-label" style="margin-top:0.75rem">الدعم</div>
            <a href="{{ route('admin.tickets.index') }}" class="nav-item {{ request()->routeIs('admin.tickets.index') || request()->routeIs('admin.tickets.show') || request()->routeIs('admin.tickets.update-status') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/><rect x="1" y="5" width="22" height="14" rx="3"/></svg>
                التذاكر
                @php $pendingCount = \App\Models\Ticket::where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span style="margin-right:auto;font-size:0.7rem;font-weight:700;background:rgba(245,158,11,0.2);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);padding:0.1rem 0.45rem;border-radius:999px">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.ticket-types.index') }}" class="nav-item {{ request()->routeIs('admin.ticket-types.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                أنواع التذاكر
            </a>

            <div class="nav-group-label" style="margin-top:0.75rem">المستخدمون</div>
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                المستخدمون
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('home') }}" class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                العودة للموقع
            </a>
            <form method="POST" action="{{ route('logout') }}" class="action-form" style="display:block; margin-top:2px">
                @csrf
                <button type="submit" class="nav-item" style="width:100%; border:none; background:transparent; cursor:pointer; text-align:right">
                    <svg width="16" height="16" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span style="color:var(--color-danger)">تسجيل الخروج</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main area ────────────────────────────────────────────────────── --}}
    <div class="admin-main">
        {{-- Top bar --}}
        <header class="admin-topbar">
            <div style="display:flex; align-items:center; gap:0.75rem">
                {{-- Mobile menu toggle --}}
                <button class="mobile-menu-btn"
                        style="display:none; align-items:center; justify-content:center; width:36px; height:36px;
                               border:1px solid var(--color-slate-border); border-radius:8px; background:transparent;
                               color:var(--color-text-secondary); cursor:pointer"
                        onclick="toggleAdminSidebar()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <div style="font-size:0.9375rem; font-weight:600; color:var(--color-text-secondary)">
                    @yield('topbar-title', 'لوحة التحكم')
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:0.75rem">
                <span style="font-size:0.8125rem; color:var(--color-text-muted)" class="topbar-username">{{ auth()->user()->display_name }}</span>
                <img src="{{ auth()->user()->avatar_url }}" alt="" style="width:32px;height:32px;border-radius:50%;border:2px solid var(--color-slate-border)">
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div style="margin:1rem 1.5rem; padding:0.75rem 1rem; background:rgba(16,185,129,0.12); border:1px solid rgba(16,185,129,0.3); border-radius:0.5rem; color:#34d399; font-size:0.875rem; display:flex; gap:0.5rem; align-items:center">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="margin:1rem 1.5rem; padding:0.75rem 1rem; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); border-radius:0.5rem; color:#f87171; font-size:0.875rem; display:flex; gap:0.5rem; align-items:center">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="admin-content">
            @yield('content')
        </div>
    </div>
</div>

<script>
function toggleAdminSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
</script>
@stack('scripts')
</body>
</html>
