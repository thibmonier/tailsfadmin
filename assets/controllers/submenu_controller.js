import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus submenu — tailsfadmin--submenu
 *
 * US-025 : rend les sous-menus de la sidebar réellement repliables au clavier
 * et à la souris, avec `aria-expanded` synchronisé. Remplace le `x-data` Alpine
 * vestigial (ADR-004). Le sous-menu replié est masqué via l'attribut `hidden`
 * (retiré de l'ordre de tabulation et de l'arbre d'accessibilité).
 *
 * Cibles :
 *   - button : le déclencheur (porte aria-expanded)
 *   - menu   : le <ul> du sous-menu
 */
export default class extends Controller {
    static targets = ["button", "menu"];
    static values = { open: { type: Boolean, default: false } };

    connect() {
        this.#sync();
    }

    toggle() {
        this.openValue = !this.openValue;
        this.#sync();
    }

    #sync() {
        if (this.hasButtonTarget) {
            this.buttonTarget.setAttribute("aria-expanded", String(this.openValue));
        }
        if (this.hasMenuTarget) {
            this.menuTarget.hidden = !this.openValue;
        }
    }
}
