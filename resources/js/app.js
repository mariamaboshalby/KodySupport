import './bootstrap';
import { applyLang, getLang, setLang } from './i18n';

// Expose setLang globally for the toggle button
window.switchLang = function () {
    const current = getLang();
    setLang(current === 'ar' ? 'en' : 'ar');
};

// Expose translations for inline use (e.g. confirm dialogs)
import ar from './i18n/ar.json';
import en from './i18n/en.json';
window.__i18n = { ar, en };
window.__t = function(key) {
    const lang = getLang();
    return window.__i18n[lang]?.[key] ?? window.__i18n['en']?.[key] ?? key;
};

// ── CSRF token for all fetch requests ────────────────────────────────────────
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// ── Vote: Post ────────────────────────────────────────────────────────────────
window.votePost = async function (postId, value, btn) {
    try {
        const res = await fetch(`/votes/posts/${postId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ value }),
        });

        if (res.status === 401) { window.location.href = '/login'; return; }
        if (!res.ok) return;

        const { votes, userVote } = await res.json();

        // Update vote count text
        document.querySelectorAll(`#votes-post-${postId}`).forEach(el => {
            el.textContent = votes.toLocaleString();
        });

        // Update button states within the same post card
        const card = btn.closest('[id^="post-"]') ?? btn.closest('article') ?? document;
        card.querySelectorAll('.vote-btn').forEach(b => {
            b.classList.remove('active-up', 'active-down');
        });

        if (userVote === 1) btn.classList.add('active-up');
        if (userVote === -1) btn.classList.add('active-down');

    } catch (e) { console.error('Vote failed', e); }
};

// ── Vote: Comment ─────────────────────────────────────────────────────────────
window.voteComment = async function (commentId, value, btn) {
    try {
        const res = await fetch(`/votes/comments/${commentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ value }),
        });

        if (res.status === 401) { window.location.href = '/login'; return; }
        if (!res.ok) return;

        const { votes, userVote } = await res.json();

        const countEl = document.getElementById(`votes-comment-${commentId}`);
        if (countEl) countEl.textContent = votes.toLocaleString();

        btn.classList.toggle('active-up', userVote === 1);

    } catch (e) { console.error('Vote failed', e); }
};

// ── Toggle Reply Form ─────────────────────────────────────────────────────────
window.toggleReply = function (commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    if (!form) return;
    const isHidden = form.style.display === 'none' || form.style.display === '';
    form.style.display = isHidden ? 'block' : 'none';
    if (isHidden) {
        const ta = form.querySelector('textarea');
        if (ta) ta.focus();
    }
};

// ── Auto-resize textareas ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('textarea').forEach(ta => {
        ta.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });

    // Lucide icons
    if (window.lucide) lucide.createIcons();
});

// ── Keyboard shortcut: / to focus search ─────────────────────────────────────
document.addEventListener('keydown', (e) => {
    if (e.key === '/' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        document.querySelector('.search-bar input')?.focus();
    }
});
