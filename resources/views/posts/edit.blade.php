@extends('layouts.app')
@section('title', 'Edit Post')
@section('content')
<div class="site-container" style="padding-top:2rem; padding-bottom:3rem; max-width:860px">
    <div style="margin-bottom:1.5rem">
        <h1 class="page-title" data-i18n="edit">تعديل المقال</h1>
        <p style="color:var(--color-text-muted); font-size:0.875rem; margin-top:0.25rem">
            <a href="{{ route('posts.show', $post->slug) }}" style="color:var(--color-cyan-400)">← <span data-i18n="home">الرئيسية</span></a>
        </p>
    </div>

    <form action="{{ route('posts.update', $post->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('posts._form', ['post' => $post, 'categories' => $categories, 'tags' => $tags])

        <div style="display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid var(--color-slate-border)">
            <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-ghost" data-i18n="cancel">إلغاء</a>
            <button type="submit" name="status" value="draft" class="btn btn-outline" data-i18n="save_draft">حفظ كمسودة</button>
            <button type="submit" name="status" value="published" class="btn btn-primary" data-i18n="update_publish">تحديث ونشر</button>
        </div>
    </form>
</div>
@endsection
