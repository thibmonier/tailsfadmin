import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus dropdown — tailsfadmin--dropdown
 *
 * Contrôleur générique et réutilisable (user-menu, notifications, actions de tables…).
 * Plusieurs instances sont totalement indépendantes : ouvrir l'une ne ferme pas les autres
 * (sauf si `closeOthersValue` est true).
 *
 * Cibles :
 *   - trigger : bouton déclencheur (aria-expanded synchronisé)
 *   - menu    : panneau du dropdown (hidden par défaut)
 *
 * Valeurs :
 *   - open (Boolean) : état du dropdown
 */
export default class extends Controller {
    static targets = ["trigger", "menu"];
    static values = { open: { type: Boolean, default: false } };

    connect() {
        // S'assurer que le menu est fermé au départ
        this.#syncAria();

        // Fermeture au clic extérieur
        this.#clickOutside = this.#onClickOutside.bind(this);
        document.addEventListener("click", this.#clickOutside, true);

        // Fermeture à l'Échap global
        this.#keydown = this.#onKeydown.bind(this);
        document.addEventListener("keydown", this.#keydown);
    }

    disconnect() {
        document.removeEventListener("click", this.#clickOutside, true);
        document.removeEventListener("keydown", this.#keydown);
    }

    // ─── Actions publiques ────────────────────────────────────────────────────

    /** Bascule ouvert/fermé */
    toggle(event) {
        event.stopPropagation();
        this.openValue = !this.openValue;
        this.#syncAria();
        if (this.openValue) this.#focusFirst();
    }

    /** Ferme le dropdown */
    close() {
        this.openValue = false;
        this.#syncAria();
    }

    /**
     * Navigation clavier à l'intérieur du menu.
     * Attacher sur le menu : data-action="keydown->tailsfadmin--dropdown#navigate"
     */
    navigate(event) {
        const items = this.#getMenuItems();
        if (!items.length) return;

        const current = document.activeElement;
        const idx = items.indexOf(current);

        switch (event.key) {
            case "ArrowDown":
                event.preventDefault();
                items[(idx + 1) % items.length].focus();
                break;
            case "ArrowUp":
                event.preventDefault();
                items[(idx - 1 + items.length) % items.length].focus();
                break;
            case "Home":
                event.preventDefault();
                items[0].focus();
                break;
            case "End":
                event.preventDefault();
                items[items.length - 1].focus();
                break;
            case "Escape":
                this.close();
                if (this.hasTriggerTarget) this.triggerTarget.focus();
                break;
        }
    }

    // ─── Helpers privés ──────────────────────────────────────────────────────

    #clickOutside = null;
    #keydown = null;

    #onClickOutside(event) {
        if (!this.element.contains(event.target)) {
            this.close();
        }
    }

    #onKeydown(event) {
        if (event.key === "Escape" && this.openValue) {
            this.close();
            if (this.hasTriggerTarget) this.triggerTarget.focus();
        }
    }

    #syncAria() {
        if (this.hasTriggerTarget) {
            // aria-expanded doit vivre sur un élément interactif (le <button>),
            // pas sur le div wrapper (aria-allowed-attr, WCAG).
            const btn = this.triggerTarget.matches("button")
                ? this.triggerTarget
                : this.triggerTarget.querySelector("button") || this.triggerTarget;
            btn.setAttribute("aria-expanded", String(this.openValue));
        }
        if (this.hasMenuTarget) {
            if (this.openValue) {
                this.menuTarget.removeAttribute("hidden");
            } else {
                this.menuTarget.setAttribute("hidden", "");
            }
        }
    }

    #focusFirst() {
        const items = this.#getMenuItems();
        if (items.length) items[0].focus();
    }

    #getMenuItems() {
        if (!this.hasMenuTarget) return [];
        return Array.from(
            this.menuTarget.querySelectorAll('[role="menuitem"]:not([disabled]):not([hidden])'),
        );
    }
}
