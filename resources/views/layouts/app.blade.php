<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" href="/favicon.ico">
    <title>@yield('title', config('app.name', 'support.plok.cloud')) — Community</title>
    <meta name="description" content="@yield('description', 'The community hub for ' . config('app.name'))">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    {{-- Arabic font --}}
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        /* RTL font switch */
        [dir="rtl"] body { font-family: 'Cairo', var(--font-body); }
        [dir="ltr"] body { font-family: var(--font-body); }
        /* Lang toggle button */
        .lang-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--color-slate-border);
            background: transparent;
            color: var(--color-text-secondary);
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.03em;
            transition: all 0.15s;
        }
        .lang-toggle:hover {
            border-color: var(--color-cyan-500);
            color: var(--color-cyan-400);
            background: rgba(6,182,212,0.06);
        }
    </style>
</head>
<body>

{{-- ── Navbar ──────────────────────────────────────────────────────────── --}}
<header class="navbar">
    <div class="site-container navbar-inner">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="navbar-brand">
            <div class="brand-icon">
                <svg width="16" height="16" fill="none" stroke="#020b18" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
                </svg>
            </div>
            <span data-i18n="site_name">{{ config('app.name', 'support.plok.cloud') }}</span><span class="accent">.</span>
        </a>

        {{-- Search --}}
        <form action="{{ route('posts.index') }}" method="GET" class="search-bar">
            <svg class="search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="search" name="q" value="{{ request('q') }}"
                   data-i18n-placeholder="search_placeholder"
                   placeholder="ابحث في المقالات، التوثيق، سجل التغييرات…"
                   autocomplete="off">
        </form>

        {{-- Nav Actions --}}
        <div class="navbar-actions">

            {{-- Language toggle --}}
            <button id="langToggle" class="lang-toggle" onclick="window.switchLang()" title="Switch language">
                EN
            </button>

            @guest
                <a href="{{ route('tickets.create') }}" class="btn btn-outline btn-sm">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                        <rect x="1" y="5" width="22" height="14" rx="3"/>
                    </svg>
                    احجز تذكرة
                </a>
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm" data-i18n="sign_in">تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm" data-i18n="join">انضم</a>
            @else
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span data-i18n="new_post">مقال جديد</span>
                </a>

                <a href="{{ route('bookmarks.index') }}" class="btn btn-ghost btn-icon" data-i18n-title="bookmarks" title="المحفوظات">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                </a>

                {{-- User dropdown --}}
                <div class="dropdown" id="userDropdown">
                    <button class="btn btn-ghost btn-icon" onclick="toggleDropdown('userDropdown')" style="padding:0">
                        <img src="{{ auth()->user()->avatar_url }}"
                             alt="{{ auth()->user()->display_name }}"
                             class="avatar avatar-md">
                    </button>
                    <div class="dropdown-menu">
                        <div style="padding:0.75rem 1rem; border-bottom:1px solid var(--color-slate-border)">
                            <div style="font-weight:600; font-size:0.875rem; color:var(--color-text-primary)">
                                {{ auth()->user()->display_name }}
                            </div>
                            <div style="font-size:0.75rem; color:var(--color-text-muted)">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                            <svg width="14" height="14" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            <span style="color:#f87171">لوحة التحكم</span>
                        </a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span data-i18n="profile">الملف الشخصي</span>
                        </a>
                        <a href="{{ route('bookmarks.index') }}" class="dropdown-item">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                            <span data-i18n="saved_posts">المحفوظات</span>
                        </a>
                        <a href="{{ route('tickets.index') }}" class="dropdown-item">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                                <rect x="1" y="5" width="22" height="14" rx="3"/>
                            </svg>
                            <span>إدارة التذاكر</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                <span data-i18n="sign_out">تسجيل الخروج</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>

{{-- ── Flash Messages ─────────────────────────────────────────────────── --}}
<div class="flash-container" id="flashContainer">
    @if(session('success'))
        <div class="alert alert-success animate-fade-in" style="pointer-events:all; min-width:280px">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error animate-fade-in" style="pointer-events:all; min-width:280px">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
    @endif
</div>

{{-- ── Main Content ───────────────────────────────────────────────────── --}}
<main>
    @yield('content')
</main>

{{-- ── Footer ─────────────────────────────────────────────────────────── --}}
<footer style="border-top:1px solid var(--color-slate-border); margin-top:4rem; padding:2rem 0">
    <div class="site-container" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; font-size:0.8125rem; color:var(--color-text-muted)">
        <div style="display:flex; align-items:center; gap:0.5rem">
            <div class="brand-icon" style="width:20px; height:20px">
                <svg width="10" height="10" fill="none" stroke="#020b18" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            </div>
            <span data-i18n="site_name">{{ config('app.name') }}</span> · <span data-i18n="built_with">مبني بـ Laravel 12</span>
        </div>
        <div style="display:flex; gap:1.5rem">
            <a href="{{ route('home') }}" style="color:var(--color-text-muted); text-decoration:none" data-i18n="home">الرئيسية</a>
            <a href="{{ route('posts.index') }}?type=documentation" style="color:var(--color-text-muted); text-decoration:none" data-i18n="docs">التوثيق</a>
            <a href="{{ route('posts.index') }}?type=changelog" style="color:var(--color-text-muted); text-decoration:none" data-i18n="changelog">سجل التغييرات</a>
        </div>
    </div>
</footer>

@stack('modals')
@stack('scripts')
<script>
function toggleDropdown(id) {
    const el = document.getElementById(id);
    el.classList.toggle('open');
    document.addEventListener('click', function handler(e) {
        if (!el.contains(e.target)) {
            el.classList.remove('open');
            document.removeEventListener('click', handler);
        }
    });
}

setTimeout(() => {
    document.querySelectorAll('#flashContainer .alert').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
</script>
</body>
</html>
