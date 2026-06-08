@extends('admin.layout')

@section('title', 'إدارة المستخدمين')
@section('topbar-title', 'إدارة المستخدمين')

@section('content')

<div class="admin-page-header">
    <h1>المستخدمون</h1>
    <p>إدارة حسابات المستخدمين وأدوارهم</p>
</div>

{{-- Filter bar --}}
<form method="GET" class="filter-bar">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="ابحث بالاسم أو البريد..." style="flex:1; min-width:200px">
    <select name="role">
        <option value="">كل الأدوار</option>
        <option value="user"      {{ request('role') === 'user' ? 'selected' : '' }}>مستخدم</option>
        <option value="moderator" {{ request('role') === 'moderator' ? 'selected' : '' }}>مشرف</option>
        <option value="admin"     {{ request('role') === 'admin' ? 'selected' : '' }}>أدمن</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">بحث</button>
    @if(request()->hasAny(['q','role']))
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">مسح</a>
    @endif
    <span style="margin-right:auto; font-size:0.8125rem; color:var(--color-text-muted)">{{ $users->total() }} مستخدم</span>
</form>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>المستخدم</th>
                <th>البريد</th>
                <th>الدور</th>
                <th>المقالات</th>
                <th>التعليقات</th>
                <th>السمعة</th>
                <th>تاريخ التسجيل</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td style="color:var(--color-text-muted); font-size:0.75rem">{{ $user->id }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.625rem">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:32px;height:32px;border-radius:50%;flex-shrink:0">
                        <div>
                            <div style="font-weight:500; color:var(--color-text-primary)">{{ $user->display_name }}</div>
                            @if($user->username)
                            <div style="font-size:0.75rem; color:var(--color-text-muted)">@{{ $user->username }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="font-size:0.8125rem">{{ $user->email }}</td>
                <td>
                    <span class="role-badge role-{{ $user->role }}">
                        {{ match($user->role) { 'admin' => 'أدمن', 'moderator' => 'مشرف', default => 'مستخدم' } }}
                    </span>
                </td>
                <td style="text-align:center">{{ $user->posts_count }}</td>
                <td style="text-align:center">{{ $user->comments_count }}</td>
                <td style="text-align:center; color:var(--color-cyan-400); font-weight:600">{{ $user->reputation }}</td>
                <td style="font-size:0.75rem; white-space:nowrap">{{ $user->created_at->format('Y/m/d') }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:0.375rem">
                        {{-- Change role --}}
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="action-form" style="display:inline-flex; align-items:center; gap:0.25rem">
                            @csrf @method('PATCH')
                            <select name="role" style="background:var(--color-surface-700); border:1px solid var(--color-slate-border); border-radius:0.375rem; padding:0.25rem 0.5rem; font-size:0.75rem; color:var(--color-text-primary); font-family:'Cairo',sans-serif">
                                <option value="user"      {{ $user->role === 'user'      ? 'selected' : '' }}>مستخدم</option>
                                <option value="moderator" {{ $user->role === 'moderator' ? 'selected' : '' }}>مشرف</option>
                                <option value="admin"     {{ $user->role === 'admin'     ? 'selected' : '' }}>أدمن</option>
                            </select>
                            <button type="submit" class="btn btn-outline btn-sm" title="حفظ الدور">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            </button>
                        </form>

                        {{-- Delete user --}}
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="action-form" onsubmit="return confirm('هل تريد حذف هذا المستخدم؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-icon" title="حذف">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </form>
                        @else
                        <span style="font-size:0.75rem; color:var(--color-text-muted)">(أنت)</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding:2rem; color:var(--color-text-muted)">لا يوجد مستخدمون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($users->hasPages())
<div style="margin-top:1rem; display:flex; justify-content:center">
    {{ $users->withQueryString()->links() }}
</div>
@endif

@endsection
