import ar from './i18n/ar.json';
import en from './i18n/en.json';

const translations = { ar, en };

// Get saved language or default to Arabic
export function getLang() {
    return localStorage.getItem('lang') || 'ar';
}

export function setLang(lang) {
    localStorage.setItem('lang', lang);
    applyLang(lang);
}

export function t(key) {
    const lang = getLang();
    return translations[lang]?.[key] ?? translations['en']?.[key] ?? key;
}

export function applyLang(lang) {
    const strings = translations[lang];
    if (!strings) return;

    // Set dir and lang on <html>
    document.documentElement.setAttribute('dir', strings.dir);
    document.documentElement.setAttribute('lang', strings.lang);

    // Translate all elements with data-i18n attribute
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (strings[key] !== undefined) {
            el.textContent = strings[key];
        }
    });

    // Translate placeholders
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.getAttribute('data-i18n-placeholder');
        if (strings[key] !== undefined) {
            el.setAttribute('placeholder', strings[key]);
        }
    });

    // Translate title attributes
    document.querySelectorAll('[data-i18n-title]').forEach(el => {
        const key = el.getAttribute('data-i18n-title');
        if (strings[key] !== undefined) {
            el.setAttribute('title', strings[key]);
        }
    });

    // Update lang toggle button text
    const toggleBtn = document.getElementById('langToggle');
    if (toggleBtn) {
        toggleBtn.textContent = lang === 'ar' ? 'EN' : 'ع';
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    applyLang(getLang());
});
