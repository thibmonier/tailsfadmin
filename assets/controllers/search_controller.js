import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus search — tailsfadmin--search
 *
 * Raccourcis clavier :
 *   - Cmd/Ctrl+K  → ouvre la recherche et donne le focus au champ
 *   - /           → idem, sans insérer "/" dans le champ
 *   - Échap       → ferme la recherche et rend le focus au déclencheur
 *
 * Les raccourcis sont ignorés si un champ de formulaire a déjà le focus
 * (input, textarea, select, [contenteditable]).
 *
 * Cibles :
 *   - input : champ de saisie de recherche
 *   - panel : conteneur de la recherche (affiché/masqué)
 */
export default class extends Controller {
    static targets = ["input", "panel"];
    static values = { open: { type: Boolean, default: false } };

    connect() {
        this.#keydown = this.#onKeydown.bind(this);
        document.addEventListener("keydown", this.#keydown);
    }

    disconnect() {
        document.removeEventListener("keydown", this.#keydown);
    }

    // ─── Actions publiques ────────────────────────────────────────────────────

    /** Ouvre la recherche et focus le champ */
    open() {
        this.openValue = true;
        this.#applyOpen();
        if (this.hasInputTarget) {
            this.inputTarget.focus();
        }
    }

    /** Ferme la recherche */
    close() {
        this.openValue = false;
        this.#applyOpen();
    }

    /** Ferme si Échap est pressé dans le champ */
    handleEscape(event) {
        if (event.key === "Escape") {
            this.close();
        }
    }

    // ─── Helpers privés ──────────────────────────────────────────────────────

    #keydown = null;

    #onKeydown(event) {
        // Ignorer si un champ de formulaire est déjà actif
        if (this.#isFormFieldFocused()) return;

        const isCmdK = (event.metaKey || event.ctrlKey) && event.key === "k";
        const isSlash = event.key === "/" && !event.metaKey && !event.ctrlKey && !event.altKey;

        if (isCmdK || isSlash) {
            event.preventDefault(); // Évite "k" ou "/" dans le champ
            this.open();
        }
    }

    #isFormFieldFocused() {
        const active = document.activeElement;
        if (!active) return false;

        const tag = active.tagName.toLowerCase();
        if (tag === "input" || tag === "textarea" || tag === "select") return true;
        if (active.isContentEditable) return true;

        // Ne pas ignorer si l'input focusé est celui de notre propre recherche
        if (this.hasInputTarget && active === this.inputTarget) return false;

        return false;
    }

    #applyOpen() {
        if (this.hasPanelTarget) {
            if (this.openValue) {
                this.panelTarget.removeAttribute("hidden");
            } else {
                this.panelTarget.setAttribute("hidden", "");
            }
        }
    }
}
