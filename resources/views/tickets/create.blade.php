@extends('layouts.app')

@section('title', 'حجز تذكرة دعم')
@section('description', 'احجز زيارة دعم فني أو استشارة')

@section('content')
<div class="site-container" style="max-width:720px; padding-top:2.5rem; padding-bottom:4rem">

    {{-- Page Header --}}
    <div style="text-align:center; margin-bottom:2.5rem">
        <div style="display:inline-flex; align-items:center; justify-content:center;
                    width:56px; height:56px; border-radius:14px;
                    background:linear-gradient(135deg,var(--color-cyan-500),#0e7fa3);
                    margin-bottom:1rem">
            <svg width="26" height="26" fill="none" stroke="#020b18" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                <rect x="1" y="5" width="22" height="14" rx="3"/>
            </svg>
        </div>
        <h1 class="page-title" style="font-size:1.875rem; margin-bottom:0.5rem">احجز تذكرة دعم</h1>
        <p style="color:var(--color-text-secondary); font-size:0.9375rem; max-width:480px; margin:0 auto">
            أدخل بياناتك وسيتواصل معك فريقنا لتأكيد الموعد في أقرب وقت
        </p>
    </div>

    {{-- Card --}}
    <div class="card" style="padding:2rem 2.25rem">

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:1.5rem">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <div style="font-weight:600; margin-bottom:0.25rem">يرجى تصحيح الأخطاء التالية:</div>
                    <ul style="margin:0; padding-right:1rem; font-size:0.8125rem">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('tickets.store') }}" method="POST" novalidate>
            @csrf

            {{-- Row 1: الاسم + اسم الشركة --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem">
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="name">
                        الاسم الكامل <span style="color:var(--color-danger)">*</span>
                    </label>
                    <input
                        id="name" name="name" type="text"
                        class="form-input @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}"
                        placeholder="مثال: أحمد محمد"
                        required
                    >
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="company_name">
                        اسم الشركة / المؤسسة
                    </label>
                    <input
                        id="company_name" name="company_name" type="text"
                        class="form-input @error('company_name') border-red-500 @enderror"
                        value="{{ old('company_name') }}"
                        placeholder="اختياري"
                    >
                    @error('company_name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Row 2: التليفون + نوع الزيارة --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem">
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="phone">
                        رقم الهاتف <span style="color:var(--color-danger)">*</span>
                    </label>
                    <input
                        id="phone" name="phone" type="tel"
                        class="form-input @error('phone') border-red-500 @enderror"
                        value="{{ old('phone') }}"
                        placeholder="مثال: 01012345678"
                        required
                    >
                    @error('phone')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="visit_type">
                        نوع الزيارة <span style="color:var(--color-danger)">*</span>
                    </label>
                    <select
                        id="visit_type" name="visit_type"
                        class="form-select @error('visit_type') border-red-500 @enderror"
                        required
                    >
                        <option value="" disabled {{ old('visit_type') ? '' : 'selected' }}>اختر نوع الزيارة</option>
                        <option value="technical_support" {{ old('visit_type') === 'technical_support' ? 'selected' : '' }}>🔧 دعم تقني</option>
                        <option value="consultation"      {{ old('visit_type') === 'consultation'      ? 'selected' : '' }}>💡 استشارة</option>
                        <option value="installation"      {{ old('visit_type') === 'installation'      ? 'selected' : '' }}>⚙️ تركيب وإعداد</option>
                        <option value="maintenance"       {{ old('visit_type') === 'maintenance'       ? 'selected' : '' }}>🛠️ صيانة</option>
                        <option value="training"          {{ old('visit_type') === 'training'          ? 'selected' : '' }}>📚 تدريب</option>
                        <option value="other"             {{ old('visit_type') === 'other'             ? 'selected' : '' }}>📋 أخرى</option>
                    </select>
                    @error('visit_type')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- العنوان --}}
            <div class="form-group">
                <label class="form-label" for="address">العنوان</label>
                <input
                    id="address" name="address" type="text"
                    class="form-input @error('address') border-red-500 @enderror"
                    value="{{ old('address') }}"
                    placeholder="مثال: القاهرة، مدينة نصر، شارع الحجاز"
                >
                @error('address')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- التكلفة المتوقعة --}}
            <div class="form-group">
                <label class="form-label" for="expected_cost">
                    التكلفة المتوقعة (بالجنيه المصري)
                </label>
                <div style="position:relative">
                    <input
                        id="expected_cost" name="expected_cost" type="number"
                        class="form-input @error('expected_cost') border-red-500 @enderror"
                        value="{{ old('expected_cost') }}"
                        placeholder="0.00"
                        min="0" step="0.01"
                        style="padding-left:3rem"
                    >
                    <span style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%);
                                 color:var(--color-text-muted); font-size:0.875rem; pointer-events:none">
                        ج.م
                    </span>
                </div>
                <p class="form-hint">اتركه فارغاً إذا لم تكن متأكداً من التكلفة</p>
                @error('expected_cost')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- ملاحظات --}}
            <div class="form-group">
                <label class="form-label" for="notes">ملاحظات إضافية</label>
                <textarea
                    id="notes" name="notes"
                    class="form-textarea @error('notes') border-red-500 @enderror"
                    rows="4"
                    placeholder="أي تفاصيل إضافية تساعدنا على تقديم خدمة أفضل…"
                    style="min-height:100px"
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Divider --}}
            <hr style="border:none; border-top:1px solid var(--color-slate-border); margin:1.5rem 0">

            {{-- Submit --}}
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem">
                <p style="font-size:0.8125rem; color:var(--color-text-muted); margin:0">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; vertical-align:-2px; margin-left:4px">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    بياناتك محمية ولن يتم مشاركتها مع أي طرف ثالث
                </p>
                <button type="submit" class="btn btn-primary" style="padding:0.625rem 2rem; font-size:1rem">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                        <rect x="1" y="5" width="22" height="14" rx="3"/>
                    </svg>
                    احجز التذكرة
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
