<div style="display:grid; grid-template-columns:1fr 260px; gap:1.5rem; align-items:start">

    {{-- Left: main fields --}}
    <div>
        {{-- Title --}}
        <div class="form-group">
            <label class="form-label" for="title"><span data-i18n="title">العنوان</span> <span style="color:var(--color-danger)">*</span></label>
            <input type="text" id="title" name="title" class="form-input"
                   value="{{ old('title', $post?->title) }}"
                   data-i18n-placeholder="title_placeholder"
                   placeholder="عنوان واضح ومعبر…" required>
            @error('title')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Excerpt --}}
        <div class="form-group">
            <label class="form-label" for="excerpt" data-i18n="excerpt">ملخص</label>
            <textarea id="excerpt" name="excerpt" rows="2" class="form-textarea" style="min-height:60px"
                      data-i18n-placeholder="excerpt_placeholder"
                      placeholder="ملخص قصير يظهر في قائمة المقالات (اختياري)…">{{ old('excerpt', $post?->excerpt) }}</textarea>
            @error('excerpt')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Body --}}
        <div class="form-group">
            <label class="form-label" for="body">
                Content <span style="color:var(--color-danger)">*</span>
                <span style="font-weight:400; color:var(--color-text-muted); font-size:0.8125rem">— Markdown-like formatting supported</span>
            </label>

            {{-- Editor Toolbar --}}
            <div style="display:flex; gap:0.5rem; padding:0.5rem 0.75rem; background:var(--color-surface-900); border:1px solid var(--color-slate-border); border-bottom:none; border-radius:8px 8px 0 0; flex-wrap:wrap">
                <button type="button" onclick="insertMarkdown('**', '**', 'bold text')" class="editor-tool-btn" title="Bold">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>
                </button>
                <button type="button" onclick="insertMarkdown('*', '*', 'italic text')" class="editor-tool-btn" title="Italic">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
                </button>
                <button type="button" onclick="insertMarkdown('`', '`', 'code')" class="editor-tool-btn" title="Inline code">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                </button>
                <button type="button" onclick="insertMarkdown('\n```\n', '\n```\n', 'code block')" class="editor-tool-btn" title="Code block">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="9" x2="9" y2="15"/><line x1="15" y1="9" x2="15" y2="15"/></svg>
                </button>
                <button type="button" onclick="insertMarkdown('## ', '', 'Heading')" class="editor-tool-btn" title="Heading">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 12h16M4 6h16M4 18h16"/></svg>
                </button>
                <div style="width:1px; background:var(--color-slate-border); margin:0 0.25rem"></div>
                {{-- Image upload button --}}
                <button type="button" onclick="document.getElementById('inlineImageInput').click()" class="editor-tool-btn" title="Insert image">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span style="font-size:0.75rem; margin-left:3px">Image</span>
                </button>
                <input type="file" id="inlineImageInput" accept="image/*" style="display:none" onchange="uploadInlineImage(this)">
                {{-- YouTube button --}}
                <button type="button" onclick="insertYoutube()" class="editor-tool-btn" title="Embed YouTube video">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2C0 8.1 0 12 0 12s0 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1c.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8zM9.75 15.6V8.4l6.3 3.6-6.3 3.6z"/></svg>
                    <span style="font-size:0.75rem; margin-left:3px">YouTube</span>
                </button>
                {{-- Uploading indicator --}}
                <span id="uploadingIndicator" style="display:none; font-size:0.75rem; color:var(--color-text-muted); align-items:center; gap:0.35rem">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="animation:spin 1s linear infinite"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
                    Uploading…
                </span>
            </div>

            <textarea id="body" name="body" rows="16" class="form-textarea"
                      style="font-family:var(--font-mono); font-size:0.9rem; border-radius:0 0 8px 8px; border-top:none"
                      placeholder="Write your content here…

Use ## for headings, **bold**, *italic*, and \`\`\`code blocks\`\`\`…

Images: click the Image button above to upload.
YouTube: click the YouTube button and paste the video URL." required>{{ old('body', $post?->body) }}</textarea>
            @error('body')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Cover Image --}}
        <div class="form-group">
            <label class="form-label" for="cover_image" data-i18n="cover_image">صورة الغلاف</label>
            @if($post?->cover_image)
            <div style="margin-bottom:0.75rem; border-radius:8px; overflow:hidden; height:120px; position:relative">
                <img src="{{ asset('storage/' . $post->cover_image) }}" alt="Cover"
                     style="width:100%; height:100%; object-fit:cover">
                <label style="position:absolute; top:8px; right:8px; display:flex; align-items:center; gap:0.4rem;
                               background:rgba(10,18,30,0.85); border:1px solid rgba(239,68,68,0.5);
                               color:#f87171; font-size:0.75rem; font-weight:500;
                               padding:0.3rem 0.6rem; border-radius:6px; cursor:pointer;
                               backdrop-filter:blur(4px); transition:background 0.15s"
                      onmouseover="this.style.background='rgba(239,68,68,0.2)'"
                      onmouseout="this.style.background='rgba(10,18,30,0.85)'">
                    <input type="checkbox" name="remove_cover_image" value="1" style="display:none">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Remove image
                </label>
            </div>
            <script>
            // When "Remove image" is checked, show visual feedback and hide the preview
            document.querySelector('[name="remove_cover_image"]')?.addEventListener('change', function() {
                const wrapper = this.closest('.form-group').querySelector('[style*="height:120px"]');
                if (this.checked) {
                    wrapper.style.opacity = '0.4';
                    this.parentElement.innerHTML = '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Will be removed';
                    this.parentElement.style.background = 'rgba(239,68,68,0.2)';
                }
            });
            </script>
            @endif
            <input type="file" id="cover_image" name="cover_image" accept="image/*"
                   style="background:var(--color-surface-800); border:1px solid var(--color-slate-border); border-radius:8px; padding:0.5rem; color:var(--color-text-secondary); font-size:0.875rem; width:100%; cursor:pointer">
            <p class="form-hint">Max 4MB. JPG, PNG, WebP.</p>
            @error('cover_image')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Right: options panel --}}
    <div style="display:flex; flex-direction:column; gap:1rem; position:sticky; top:76px">

        {{-- Type --}}
        <div class="card" style="padding:1rem">
            <label class="form-label" for="type" data-i18n="post_type">نوع المقال</label>
            <select id="type" name="type" class="form-select">
                @foreach(['post' => 'نقاش', 'announcement' => 'إعلان', 'documentation' => 'توثيق', 'changelog' => 'سجل تغييرات'] as $value => $label)
                <option value="{{ $value }}" data-i18n="post_type_{{ $value }}" {{ old('type', $post?->type ?? 'post') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Category --}}
        <div class="card" style="padding:1rem">
            <label class="form-label" for="category_id" data-i18n="category">التصنيف</label>
            <select id="category_id" name="category_id" class="form-select">
                <option value="" data-i18n="none">— لا شيء —</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $post?->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Tags --}}
        <div class="card" style="padding:1rem">
            <label class="form-label" data-i18n="tags">الوسوم</label>
            <div style="display:flex; flex-wrap:wrap; gap:0.5rem">
                @foreach($tags as $tag)
                @php $selected = old('tags') ? in_array($tag->id, old('tags')) : ($post && $post->tags->contains($tag->id)); @endphp
                <label style="display:flex; align-items:center; gap:0.35rem; cursor:pointer">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                           {{ $selected ? 'checked' : '' }}
                           style="accent-color:var(--color-cyan-500); width:14px; height:14px">
                    <span class="tag-pill" style="{{ $selected ? 'background:rgba(34,211,238,0.12); border-color:rgba(34,211,238,0.4); color:var(--color-cyan-400)' : '' }}">
                        {{ $tag->name }}
                    </span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Moderation (admin/mod only) --}}
        @if(auth()->user()?->isModerator())
        <div class="card" style="padding:1rem">
            <label class="form-label" style="margin-bottom:0.75rem" data-i18n="moderation">الإشراف</label>
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; margin-bottom:0.5rem">
                <input type="checkbox" name="is_pinned" value="1"
                       {{ old('is_pinned', $post?->is_pinned) ? 'checked' : '' }}
                       style="accent-color:var(--color-cyan-500); width:14px; height:14px">
                <span style="font-size:0.875rem; color:var(--color-text-secondary)" data-i18n="pin_post">تثبيت المقال</span>
            </label>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.editor-tool-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.5rem;
    border-radius: 5px;
    border: 1px solid transparent;
    background: transparent;
    color: var(--color-text-secondary);
    cursor: pointer;
    font-size: 0.8125rem;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
}
.editor-tool-btn:hover {
    background: var(--color-surface-800);
    border-color: var(--color-slate-border);
    color: var(--color-text-primary);
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@push('scripts')
<script>
const bodyEl = document.getElementById('body');

function insertMarkdown(before, after, placeholder) {
    const start = bodyEl.selectionStart;
    const end   = bodyEl.selectionEnd;
    const sel   = bodyEl.value.substring(start, end) || placeholder;
    const text  = before + sel + after;
    bodyEl.setRangeText(text, start, end, 'select');
    bodyEl.focus();
    if (!bodyEl.value.substring(start, end)) {
        // Move cursor inside markers
        bodyEl.selectionStart = start + before.length;
        bodyEl.selectionEnd   = start + before.length + placeholder.length;
    }
}

function insertYoutube() {
    const url = prompt('Paste the YouTube video URL:');
    if (!url) return;
    // Extract video ID from various YouTube URL formats
    const match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([A-Za-z0-9_-]{11})/);
    if (!match) {
        alert('Could not find a valid YouTube video ID. Please check the URL.');
        return;
    }
    const id    = match[1];
    const tag   = `\n[youtube:${id}]\n`;
    const pos   = bodyEl.selectionStart;
    bodyEl.setRangeText(tag, pos, pos, 'end');
    bodyEl.focus();
}

function uploadInlineImage(input) {
    if (!input.files.length) return;
    const indicator = document.getElementById('uploadingIndicator');
    indicator.style.display = 'inline-flex';

    const fd = new FormData();
    fd.append('image', input.files[0]);
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch('{{ route("upload.image") }}', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.url) {
                const alt = input.files[0].name.replace(/\.[^.]+$/, '');
                const tag = `\n![${alt}](${data.url})\n`;
                const pos = bodyEl.selectionStart;
                bodyEl.setRangeText(tag, pos, pos, 'end');
                bodyEl.focus();
            } else {
                alert('Upload failed. Please try again.');
            }
        })
        .catch(() => alert('Upload failed. Please check your connection.'))
        .finally(() => {
            indicator.style.display = 'none';
            input.value = '';
        });
}
</script>
@endpush
