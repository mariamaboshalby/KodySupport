@extends('layouts.app')
@section('title', 'الملف الشخصي')

@section('content')
<div class="site-container" style="max-width:700px; padding-top:2rem; padding-bottom:4rem">

    <div class="page-header" style="margin-bottom:2rem">
        <h1 class="page-title">الملف الشخصي</h1>
        <p style="color:var(--color-text-muted); font-size:0.875rem; margin-top:0.25rem">إدارة معلومات حسابك وإعدادات الأمان</p>
    </div>

    {{-- Profile Information --}}
    <div class="card" style="padding:1.75rem; margin-bottom:1.25rem">
        <h2 style="font-size:1rem; font-weight:700; color:var(--color-text-primary); margin-bottom:0.375rem">معلومات الحساب</h2>
        <p style="font-size:0.875rem; color:var(--color-text-muted); margin-bottom:1.5rem">تحديث اسمك وعنوان بريدك الإلكتروني</p>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf @method('patch')

            <div class="form-group">
                <label class="form-label" for="name">الاسم</label>
                <input type="text" id="name" name="name" class="form-input"
                       value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name', 'updateProfileInformation')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="form-input"
                       value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email', 'updateProfileInformation')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning" style="margin-top:0.75rem">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        بريدك الإلكتروني غير موثق.
                        <button form="send-verification" class="btn btn-ghost btn-sm" style="color:var(--color-cyan-400); padding:0; text-decoration:underline">
                            اضغط هنا لإعادة الإرسال
                        </button>
                        @if (session('status') === 'verification-link-sent')
                        <p style="margin-top:0.25rem; color:var(--color-success); font-size:0.8125rem">تم إرسال رابط التحقق.</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div style="display:flex; align-items:center; gap:1rem">
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                @if (session('status') === 'profile-updated')
                <p style="font-size:0.875rem; color:var(--color-success)">✓ تم الحفظ</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="card" style="padding:1.75rem; margin-bottom:1.25rem">
        <h2 style="font-size:1rem; font-weight:700; color:var(--color-text-primary); margin-bottom:0.375rem">كلمة المرور</h2>
        <p style="font-size:0.875rem; color:var(--color-text-muted); margin-bottom:1.5rem">استخدم كلمة مرور طويلة وعشوائية لتأمين حسابك</p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf @method('put')

            <div class="form-group">
                <label class="form-label" for="current_password">كلمة المرور الحالية</label>
                <input type="password" id="current_password" name="current_password" class="form-input" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password">كلمة المرور الجديدة</label>
                <input type="password" id="new_password" name="password" class="form-input" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex; align-items:center; gap:1rem">
                <button type="submit" class="btn btn-primary">تحديث كلمة المرور</button>
                @if (session('status') === 'password-updated')
                <p style="font-size:0.875rem; color:var(--color-success)">✓ تم التحديث</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Delete Account --}}
    <div class="card" style="padding:1.75rem; border-color:rgba(239,68,68,0.2)">
        <h2 style="font-size:1rem; font-weight:700; color:#f87171; margin-bottom:0.375rem">حذف الحساب</h2>
        <p style="font-size:0.875rem; color:var(--color-text-muted); margin-bottom:1.5rem">
            بمجرد حذف حسابك، سيتم حذف جميع البيانات المرتبطة به نهائياً. يرجى التأكد قبل المتابعة.
        </p>

        <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteModal').style.display='flex'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="m19 6-.867 12.142A2 2 0 0 1 16.138 20H7.862a2 2 0 0 1-1.995-1.858L5 6m5 0V4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2"/></svg>
            حذف الحساب
        </button>
    </div>

</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal"
     style="display:none; position:fixed; inset:0; z-index:1000; background:rgba(0,0,0,0.65); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem">
    <div class="card" style="width:100%; max-width:420px; padding:1.75rem; border-color:rgba(239,68,68,0.3)">
        <h2 style="font-size:1.125rem; font-weight:700; color:var(--color-text-primary); margin-bottom:0.5rem">تأكيد الحذف</h2>
        <p style="font-size:0.875rem; color:var(--color-text-muted); margin-bottom:1.5rem">
            لا يمكن التراجع عن هذا الإجراء. أدخل كلمة مرورك للتأكيد.
        </p>

        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf @method('delete')

            <div class="form-group">
                <label class="form-label" for="delete_password">كلمة المرور</label>
                <input type="password" id="delete_password" name="password" class="form-input"
                       placeholder="••••••••" style="border-color:rgba(239,68,68,0.4)">
                @error('password', 'userDeletion')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.25rem">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('deleteModal').style.display='none'">إلغاء</button>
                <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
            </div>
        </form>
    </div>
</div>
@endsection
