import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus tabs — tailsfadmin--tabs (US-036).
 *
 * Onglets accessibles suivant le pattern ARIA « Tabs » :
 *   - clic sur un onglet → active l'onglet + son panneau ;
 *   - navigation clavier : Flèches gauche/droite, Home, End ;
 *   - un seul onglet focusable à la fois (roving tabindex).
 *
 * Aucune dépendance externe. Cibles : `tab` (boutons role="tab"),
 * `panel` (role="tabpanel"). Chaque cible porte `data-tab-id`.
 */
export default class extends Controller {
    static targets = ["tab", "panel"];
    static values = { active: String };

    connect() {
        this.#activate(this.activeValue || this.#firstId(), false);
    }

    /** Clic sur un onglet. */
    select(event) {
        const id = event.currentTarget.dataset.tabId;
        if (id) this.#activate(id, true);
    }

    /** Navigation clavier au sein du tablist. */
    navigate(event) {
        const ids = this.tabTargets.map((t) => t.dataset.tabId);
        if (!ids.length) return;

        const currentIdx = ids.indexOf(this.activeValue);
        let nextIdx = null;

        switch (event.key) {
            case "ArrowRight":
            case "ArrowDown":
                nextIdx = (currentIdx + 1) % ids.length;
                break;
            case "ArrowLeft":
            case "ArrowUp":
                nextIdx = (currentIdx - 1 + ids.length) % ids.length;
                break;
            case "Home":
                nextIdx = 0;
                break;
            case "End":
                nextIdx = ids.length - 1;
                break;
            default:
                return;
        }

        event.preventDefault();
        this.#activate(ids[nextIdx], true);
    }

    // ─── Helpers privés ──────────────────────────────────────────────────────

    #activate(id, focus) {
        if (!id) return;
        this.activeValue = id;

        for (const tab of this.tabTargets) {
            const selected = tab.dataset.tabId === id;
            tab.setAttribute("aria-selected", String(selected));
            tab.tabIndex = selected ? 0 : -1;
            if (selected && focus) tab.focus();
        }

        for (const panel of this.panelTargets) {
            if (panel.dataset.tabId === id) {
                panel.removeAttribute("hidden");
            } else {
                panel.setAttribute("hidden", "");
            }
        }
    }

    #firstId() {
        return this.hasTabTarget ? this.tabTargets[0].dataset.tabId : "";
    }
}
