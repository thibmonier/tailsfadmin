import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus modal — tailsfadmin--modal
 * US-011 : open/close, focus trap (Tab/Shift+Tab cyclique), fermeture Échap
 * + clic overlay, restitution du focus au déclencheur, scroll body verrouillé.
 *
 * Pattern focus trap réutilisé depuis sidebar_controller.js (drawer mobile, US-006).
 *
 * Cibles :
 *   - panel   : l'élément dialog (role="dialog", aria-modal="true")
 *   - overlay : le backdrop semi-transparent (fermeture au clic)
 *
 * Actions :
 *   data-action="click->tailsfadmin--modal#open"   sur le déclencheur (wrapper du trigger)
 *   data-action="click->tailsfadmin--modal#close"  sur le bouton de fermeture et l'overlay
 *
 * Remplace l'implémentation Alpine.js x-show/x-data (ADR-004).
 */
export default class extends Controller {
    static targets = ["panel", "overlay"];

    /** Sélecteur des éléments focusables (même liste que sidebar_controller). */
    static #FOCUSABLE = [
        "a[href]",
        "button:not([disabled])",
        "input:not([disabled])",
        "select:not([disabled])",
        "textarea:not([disabled])",
        "[tabindex]:not([tabindex=\"-1\"])",
    ].join(", ");

    /** Référence vers l'élément qui avait le focus avant l'ouverture. */
    #openerElement = null;

    /** Handler du focus trap (Tab/Shift+Tab cyclique). */
    #focusTrapHandler = null;

    /** Handler global keydown (Échap). */
    #keydownHandler = null;

    connect() {
        this.#keydownHandler = this.#onKeydown.bind(this);
        document.addEventListener("keydown", this.#keydownHandler);
    }

    disconnect() {
        document.removeEventListener("keydown", this.#keydownHandler);
        // Déverrouiller le scroll si la page est détruite pendant l'ouverture
        document.body.classList.remove("overflow-hidden");
        this.#releaseFocus();
    }

    // ─── Actions publiques ────────────────────────────────────────────────────

    /**
     * Ouvre la modale.
     * Sauvegarde le document.activeElement pour la restitution du focus à la fermeture.
     */
    open() {
        // Sauvegarde l'élément courant AVANT que le modal déplace le focus
        this.#openerElement = document.activeElement;

        if (!this.hasPanelTarget) return;

        this.panelTarget.removeAttribute("hidden");
        document.body.classList.add("overflow-hidden");
        this.#trapFocus();
    }

    /**
     * Ferme la modale et restitue le focus au déclencheur original.
     */
    close() {
        if (!this.hasPanelTarget) return;

        this.panelTarget.setAttribute("hidden", "");
        document.body.classList.remove("overflow-hidden");
        this.#releaseFocus();

        // Restitution du focus au déclencheur
        if (this.#openerElement && typeof this.#openerElement.focus === "function") {
            this.#openerElement.focus();
            this.#openerElement = null;
        }
    }

    // ─── Helpers privés ──────────────────────────────────────────────────────

    /** Ferme la modale sur Échap. */
    #onKeydown(event) {
        if (event.key === "Escape" && this.hasPanelTarget && !this.panelTarget.hasAttribute("hidden")) {
            this.close();
        }
    }

    /**
     * Focus trap : garde le focus dans le panel pendant que la modale est ouverte.
     * Implémentation identique au pattern éprouvé du sidebar_controller (drawer mobile).
     */
    #trapFocus() {
        if (!this.hasPanelTarget) return;

        const focusable = Array.from(
            this.panelTarget.querySelectorAll(this.constructor.#FOCUSABLE)
        ).filter((el) => !el.closest("[hidden]"));

        if (!focusable.length) return;

        const first = focusable[0];
        const last  = focusable[focusable.length - 1];

        // Placer le focus sur le premier élément focusable du dialog
        first.focus();

        this.#focusTrapHandler = (e) => {
            if (e.key !== "Tab") return;

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

        this.panelTarget.addEventListener("keydown", this.#focusTrapHandler);
    }

    /** Libère le focus trap. */
    #releaseFocus() {
        if (!this.hasPanelTarget || !this.#focusTrapHandler) return;
        this.panelTarget.removeEventListener("keydown", this.#focusTrapHandler);
        this.#focusTrapHandler = null;
    }
}
