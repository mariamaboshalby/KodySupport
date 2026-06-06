@extends('layouts.app')
@section('title', 'Join the Community')

@section('content')
<div style="min-height:calc(100vh - 200px); display:flex; align-items:center; justify-content:center; padding:2rem 1rem">
    <div style="width:100%; max-width:420px">

        <div style="text-align:center; margin-bottom:2rem">
            <div class="brand-icon" style="width:48px; height:48px; border-radius:12px; margin:0 auto 1rem">
                <svg width="24" height="24" fill="none" stroke="#020b18" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            </div>
            <h1 style="font-size:1.5rem; font-weight:700; color:var(--color-text-primary)" data-i18n="register">إنشاء حساب</h1>
            <p style="color:var(--color-text-muted); font-size:0.875rem; margin-top:0.25rem" data-i18n="join">انضم إلى المجتمع مجاناً</p>
        </div>

        <div class="card" style="padding:2rem">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name" data-i18n="name">الاسم</label>
                    <input type="text" id="name" name="name" class="form-input"
                           value="{{ old('name') }}" required autofocus autocomplete="name"
                           placeholder="اسمك الكامل">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email" data-i18n="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="form-input"
                           value="{{ old('email') }}" required autocomplete="username"
                           placeholder="you@example.com">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password" data-i18n="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" class="form-input"
                           required autocomplete="new-password" placeholder="٨ أحرف على الأقل">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation" data-i18n="confirm_password">تأكيد كلمة المرور</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-input" required autocomplete="new-password" placeholder="أعد كتابة كلمة المرور">
                    @error('password_confirmation')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.75rem; margin-top:0.5rem" data-i18n="register">إنشاء حساب</button>
            </form>
        </div>

        <p style="text-align:center; margin-top:1.25rem; font-size:0.875rem; color:var(--color-text-muted)">
            <span data-i18n="already_registered">لديك حساب بالفعل؟</span>
            <a href="{{ route('login') }}" style="color:var(--color-cyan-400); font-weight:500" data-i18n="sign_in">تسجيل الدخول</a>
        </p>
    </div>
</div>
@endsection
