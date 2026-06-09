<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Cairo', var(--font-body); background: var(--color-navy-950); }
        </style>
    </head>
    <body>
        <div style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:2rem 1rem">
            <div style="width:100%; max-width:420px">
                <div style="text-align:center; margin-bottom:1.5rem">
                    <a href="/" style="display:inline-flex; align-items:center; gap:0.5rem; text-decoration:none">
                        <div class="brand-icon" style="width:40px; height:40px; border-radius:10px">
                            <svg width="20" height="20" fill="none" stroke="#020b18" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
                            </svg>
                        </div>
                        <span style="font-weight:700; font-size:1.125rem; color:var(--color-text-primary)">{{ config('app.name') }}</span>
                    </a>
                </div>
                <div class="card" style="padding:2rem">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
