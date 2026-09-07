import { Controller } from '@hotwired/stimulus';

/**
 * Contrôleur Stimulus "theme" — bascule clair/dark persistante.
 *
 * ADR-004 : conversion Alpine.js → Stimulus.
 * US-005   : bascule dark mode + persistance localStorage.
 *
 * Comportement :
 *   connect()  — lit localStorage.theme ; applique/retire .dark sur <html> ;
 *                fallback sur prefers-color-scheme si aucune préférence stockée.
 *   toggle()   — bascule + persiste la valeur ("dark"|"light") dans localStorage.
 *
 * Dégradation gracieuse : try/catch autour de tout accès localStorage
 * (bloqué en navigation privée sur certains navigateurs).
 */
export default class extends Controller {
    connect() {
        this.#applyTheme(this.#resolveTheme());
    }

    toggle() {
        const current = this.#resolveTheme();
        const next = current === 'dark' ? 'light' : 'dark';
        this.#persist(next);
        this.#applyTheme(next);
    }

    // ── Private ──────────────────────────────────────────────────────────────

    /** Retourne la préférence courante : localStorage, puis prefers-color-scheme. */
    #resolveTheme() {
        try {
            const stored = localStorage.getItem('theme');
            if (stored === 'dark' || stored === 'light') {
                return stored;
            }
        } catch (_) { /* localStorage bloqué */ }

        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    /** Applique ou retire la classe .dark sur <html>. */
    #applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    /** Persiste le choix dans localStorage (silencieux si bloqué). */
    #persist(theme) {
        try {
            localStorage.setItem('theme', theme);
        } catch (_) { /* localStorage bloqué, dégradation silencieuse */ }
    }
}
