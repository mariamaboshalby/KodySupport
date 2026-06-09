@extends('layouts.app')

@section('title', __('tickets.page_title'))
@section('description', __('tickets.page_subtitle'))

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
        <h1 class="page-title" style="font-size:1.875rem; margin-bottom:0.5rem" data-i18n="ticket_page_title">احجز تذكرة دعم</h1>
        <p style="color:var(--color-text-secondary); font-size:0.9375rem; max-width:480px; margin:0 auto" data-i18n="ticket_page_subtitle">
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
                    <div style="font-weight:600; margin-bottom:0.25rem" data-i18n="ticket_fix_errors">يرجى تصحيح الأخطاء التالية:</div>
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
            <div class="form-row-2col" style="margin-bottom:1.25rem">
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="name">
                        <span data-i18n="ticket_full_name">الاسم الكامل</span>
                        <span style="color:var(--color-danger)">*</span>
                    </label>
                    <input
                        id="name" name="name" type="text"
                        class="form-input @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}"
                        data-i18n-placeholder="ticket_name_placeholder"
                        placeholder="مثال: أحمد محمد"
                        required
                    >
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="company_name" data-i18n="ticket_company">
                        اسم الشركة / المؤسسة
                    </label>
                    <input
                        id="company_name" name="company_name" type="text"
                        class="form-input @error('company_name') border-red-500 @enderror"
                        value="{{ old('company_name') }}"
                        data-i18n-placeholder="ticket_company_placeholder"
                        placeholder="اختياري"
                    >
                    @error('company_name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Row 2: التليفون + نوع الزيارة --}}
            <div class="form-row-2col" style="margin-bottom:1.25rem">
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="phone">
                        <span data-i18n="ticket_phone">رقم الهاتف</span>
                        <span style="color:var(--color-danger)">*</span>
                    </label>
                    <input
                        id="phone" name="phone" type="tel"
                        class="form-input @error('phone') border-red-500 @enderror"
                        value="{{ old('phone') }}"
                        data-i18n-placeholder="ticket_phone_placeholder"
                        placeholder="مثال: 01012345678"
                        required
                    >
                    @error('phone')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="ticket_type_id">
                        <span data-i18n="ticket_visit_type">نوع الزيارة</span>
                        <span style="color:var(--color-danger)">*</span>
                    </label>
                    <select
                        id="ticket_type_id" name="ticket_type_id"
                        class="form-select @error('ticket_type_id') border-red-500 @enderror"
                        required
                        onchange="updateExpectedCost(this)"
                    >
                        <option value="" disabled {{ old('ticket_type_id') ? '' : 'selected' }} data-i18n="ticket_visit_type_placeholder">اختر نوع الزيارة</option>
                        @foreach($ticketTypes as $type)
                            <option value="{{ $type->id }}"
                                    data-cost="{{ $type->expected_cost }}"
                                    {{ old('ticket_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('ticket_type_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- التكلفة المتوقعة --}}
            <div class="form-group" id="cost-group">
                <label class="form-label" for="expected_cost" data-i18n="ticket_expected_cost">
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
                <p class="form-hint" id="cost-hint" data-i18n="ticket_cost_hint">
                    تُملأ تلقائيًا بناءً على نوع الزيارة — يمكنك تعديلها يدويًا
                </p>
                @error('expected_cost')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- العنوان --}}
            <div class="form-group">
                <label class="form-label" for="address" data-i18n="ticket_address">العنوان</label>
                <input
                    id="address" name="address" type="text"
                    class="form-input @error('address') border-red-500 @enderror"
                    value="{{ old('address') }}"
                    data-i18n-placeholder="ticket_address_placeholder"
                    placeholder="مثال: القاهرة، مدينة نصر، شارع الحجاز"
                >
                @error('address')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- ملاحظات --}}
            <div class="form-group">
                <label class="form-label" for="notes" data-i18n="ticket_notes">ملاحظات إضافية</label>
                <textarea
                    id="notes" name="notes"
                    class="form-textarea @error('notes') border-red-500 @enderror"
                    rows="4"
                    data-i18n-placeholder="ticket_notes_placeholder"
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
                    <span data-i18n="ticket_privacy">بياناتك محمية ولن يتم مشاركتها مع أي طرف ثالث</span>
                </p>
                <button type="submit" class="btn btn-primary" style="padding:0.625rem 2rem; font-size:1rem">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M15 5v2M5 5v2M15 19v2M5 19v2M3 9h18M3 15h18"/>
                        <rect x="1" y="5" width="22" height="14" rx="3"/>
                    </svg>
                    <span data-i18n="ticket_submit">احجز التذكرة</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function updateExpectedCost(select) {
    const option = select.options[select.selectedIndex];
    const cost   = option ? option.getAttribute('data-cost') : null;
    const input  = document.getElementById('expected_cost');
    const hint   = document.getElementById('cost-hint');

    if (cost !== null && cost !== '') {
        input.value = parseFloat(cost).toFixed(2);
        hint.textContent = window.__t('ticket_cost_hint_set');
        hint.style.color = 'var(--color-cyan-400)';
    } else {
        input.value = '';
        hint.textContent = window.__t('ticket_cost_hint_empty');
        hint.style.color = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('ticket_type_id');
    if (select && select.value && !document.getElementById('expected_cost').value) {
        updateExpectedCost(select);
    }
});
</script>
@endpush
