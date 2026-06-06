@extends('layouts.app')
@section('title', 'New Post')
@section('content')
<div class="site-container" style="padding-top:2rem; padding-bottom:3rem; max-width:860px">
    <div style="margin-bottom:1.5rem">
        <h1 class="page-title" data-i18n="create_post">إنشاء مقال</h1>
        <p style="color:var(--color-text-muted); font-size:0.875rem; margin-top:0.25rem" data-i18n="create_post_subtitle">شارك معرفتك، اطرح سؤالاً، أو أضف تحديثاً.</p>
    </div>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('posts._form', ['post' => null, 'categories' => $categories, 'tags' => $tags])

        <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid var(--color-slate-border)">
            <a href="{{ route('home') }}" class="btn btn-ghost" data-i18n="cancel">إلغاء</a>
            <button type="submit" name="status" value="draft" class="btn btn-outline" data-i18n="save_draft">حفظ كمسودة</button>
            <button type="submit" name="status" value="published" class="btn btn-primary" data-i18n="publish">نشر</button>
        </div>
    </form>
</div>
@endsection
