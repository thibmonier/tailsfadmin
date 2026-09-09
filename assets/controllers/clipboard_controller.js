import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus : tailsfadmin--clipboard
 * US-039 — Copie une valeur dans le presse-papiers avec retour visuel.
 *
 * Câblage Twig :
 *   data-controller="tailsfadmin--clipboard"
 *   data-tailsfadmin--clipboard-text-value="…"          (valeur à copier)
 *   data-tailsfadmin--clipboard-feedback-value="Copié !" (optionnel)
 *   data-action="tailsfadmin--clipboard#copy"
 *   <span data-tailsfadmin--clipboard-target="label">Copier</span>
 *
 * Dégradation gracieuse : utilise navigator.clipboard en contexte sécurisé,
 * retombe sur une sélection + document.execCommand('copy') sinon. Aucune
 * exception ne remonte : l'échec se traduit par un état visuel « Échec ».
 */
export default class extends Controller {
    static targets = ["label"];
    static values = {
        text: String,
        feedback: { type: String, default: "Copié !" },
        duration: { type: Number, default: 2000 },
    };

    /** Copie textValue et déclenche le retour visuel. */
    async copy() {
        const ok = await this.#write(this.textValue);
        this.#flash(ok);
    }

    // ─── Helpers privés ──────────────────────────────────────────────────────

    async #write(text) {
        if (navigator.clipboard && window.isSecureContext) {
            const ok = await this.#writeAsync(text);
            if (ok) return true;
        }
        return this.#fallbackCopy(text);
    }

    async #writeAsync(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch {
            return false;
        }
    }

    #fallbackCopy(text) {
        const area = document.createElement("textarea");
        area.value = text;
        area.setAttribute("readonly", "");
        area.style.position = "absolute";
        area.style.left = "-9999px";
        document.body.appendChild(area);
        area.select();

        let ok = false;
        try {
            ok = document.execCommand("copy");
        } catch {
            ok = false;
        }
        document.body.removeChild(area);
        return ok;
    }

    #flash(ok) {
        this.element.setAttribute("data-copied", ok ? "true" : "false");
        if (!this.hasLabelTarget) return;

        const label = this.labelTarget;
        if (label.dataset.original === undefined) {
            label.dataset.original = label.textContent;
        }
        label.textContent = ok ? this.feedbackValue : "Échec";

        window.clearTimeout(this.timeout);
        this.timeout = window.setTimeout(() => {
            label.textContent = label.dataset.original;
            this.element.removeAttribute("data-copied");
        }, this.durationValue);
    }
}
