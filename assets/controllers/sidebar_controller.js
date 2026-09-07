import { Controller } from '@hotwired/stimulus';

/**
 * Contrôleur Stimulus sidebar — tailsfadmin--sidebar
 *
 * Fonctionnalités :
 *   - Desktop : collapse / expand avec persistance localStorage
 *   - Mobile  : drawer (slide-in) + overlay + focus trap + fermeture Échap
 *
 * Cibles :
 *   - sidebar : l'élément <aside> principal
 *   - overlay : le fond semi-transparent mobile
 *
 * Valeurs :
 *   - open (Boolean) : état du drawer mobile
 *   - collapsed (Boolean) : état collapsed desktop
 */
export default class extends Controller {
    static targets = ['sidebar', 'overlay'];
    static values  = {
        open:      { type: Boolean, default: false },
        collapsed: { type: Boolean, default: false },
    };

    connect() {
        // Restaurer l'état desktop depuis localStorage
        const stored = this.#storageGet('sidebar-collapsed');
        if (stored !== null) {
            this.collapsedValue = stored === 'true';
        }
        this.#applyCollapsed();

        // Fermer le drawer mobile sur Échap
        this.#handleKeydown = this.#onKeydown.bind(this);
        document.addEventListener('keydown', this.#handleKeydown);
    }

    disconnect() {
        document.removeEventListener('keydown', this.#handleKeydown);
    }

    // ─── Actions publiques ────────────────────────────────────────────────────

    /** Ouvre le drawer mobile */
    open() {
        this.openValue = true;
        this.#applyOpen();
        this.#trapFocus();
    }

    /** Ferme le drawer mobile */
    close() {
        this.openValue = false;
        this.#applyOpen();
        this.#releaseFocus();
    }

    /** Bascule collapse/expand desktop */
    toggle() {
        this.collapsedValue = !this.collapsedValue;
        this.#storageSet('sidebar-collapsed', String(this.collapsedValue));
        this.#applyCollapsed();
    }

    // ─── Helpers privés ──────────────────────────────────────────────────────

    #handleKeydown = null;

    #onKeydown(event) {
        if (event.key === 'Escape' && this.openValue) {
            this.close();
        }
    }

    #applyOpen() {
        if (!this.hasSidebarTarget) return;

        if (this.openValue) {
            this.sidebarTarget.classList.remove('-translate-x-full');
            this.sidebarTarget.classList.add('translate-x-0');
            if (this.hasOverlayTarget) {
                this.overlayTarget.classList.remove('hidden');
            }
            document.body.classList.add('overflow-hidden');
        } else {
            this.sidebarTarget.classList.add('-translate-x-full');
            this.sidebarTarget.classList.remove('translate-x-0');
            if (this.hasOverlayTarget) {
                this.overlayTarget.classList.add('hidden');
            }
            document.body.classList.remove('overflow-hidden');
        }
    }

    #applyCollapsed() {
        if (!this.hasSidebarTarget) return;

        if (this.collapsedValue) {
            this.sidebarTarget.classList.add('sidebar-collapsed');
            this.sidebarTarget.classList.remove('w-[290px]');
            this.sidebarTarget.classList.add('w-[88px]');
        } else {
            this.sidebarTarget.classList.remove('sidebar-collapsed');
            this.sidebarTarget.classList.add('w-[290px]');
            this.sidebarTarget.classList.remove('w-[88px]');
        }
    }

    /** Focus trap : garde le focus dans la sidebar quand le drawer est ouvert */
    #trapFocus() {
        if (!this.hasSidebarTarget) return;

        const focusable = this.sidebarTarget.querySelectorAll(
            'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const first = focusable[0];
        const last  = focusable[focusable.length - 1];

        if (!first) return;
        first.focus();

        this.#focusTrapHandler = (e) => {
            if (e.key !== 'Tab') return;
            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        };
        this.sidebarTarget.addEventListener('keydown', this.#focusTrapHandler);
    }

    #releaseFocus() {
        if (!this.hasSidebarTarget || !this.#focusTrapHandler) return;
        this.sidebarTarget.removeEventListener('keydown', this.#focusTrapHandler);
        this.#focusTrapHandler = null;
    }

    #focusTrapHandler = null;

    #storageGet(key) {
        try { return localStorage.getItem(key); } catch { return null; }
    }

    #storageSet(key, value) {
        try { localStorage.setItem(key, value); } catch { /* ignoré */ }
    }
}
