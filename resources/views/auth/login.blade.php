@extends('layouts.app')
@section('title', 'Sign In')

@section('content')
<div style="min-height:calc(100vh - 200px); display:flex; align-items:center; justify-content:center; padding:2rem 1rem">
    <div style="width:100%; max-width:420px">

        <div style="text-align:center; margin-bottom:2rem">
            <div class="brand-icon" style="width:48px; height:48px; border-radius:12px; margin:0 auto 1rem">
                <svg width="24" height="24" fill="none" stroke="#020b18" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            </div>
            <h1 style="font-size:1.5rem; font-weight:700; color:var(--color-text-primary)" data-i18n="login">تسجيل الدخول</h1>
            <p style="color:var(--color-text-muted); font-size:0.875rem; margin-top:0.25rem" data-i18n="sign_in">سجل دخولك للمتابعة</p>
        </div>

        <div class="card" style="padding:2rem">
            @if(session('status'))
            <div class="alert alert-success" style="margin-bottom:1.25rem">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email" data-i18n="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="form-input"
                           value="{{ old('email') }}" required autofocus autocomplete="username"
                           placeholder="you@example.com">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.375rem">
                        <label class="form-label" for="password" style="margin:0" data-i18n="password">كلمة المرور</label>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:0.8125rem; color:var(--color-cyan-400)" data-i18n="forgot_password">نسيت كلمة المرور؟</a>
                        @endif
                    </div>
                    <input type="password" id="password" name="password" class="form-input"
                           required autocomplete="current-password" placeholder="••••••••">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; margin-bottom:1.25rem">
                    <input type="checkbox" name="remember" style="accent-color:var(--color-cyan-500); width:15px; height:15px">
                    <span style="font-size:0.875rem; color:var(--color-text-secondary)" data-i18n="remember_me">تذكرني</span>
                </label>

                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.75rem" data-i18n="sign_in">تسجيل الدخول</button>
            </form>
        </div>

        <p style="text-align:center; margin-top:1.25rem; font-size:0.875rem; color:var(--color-text-muted)">
            <span data-i18n="not_registered">ليس لديك حساب؟</span>
            <a href="{{ route('register') }}" style="color:var(--color-cyan-400); font-weight:500" data-i18n="join">انضم الآن</a>
        </p>
    </div>
</div>
@endsection
