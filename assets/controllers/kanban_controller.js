import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus : tailsfadmin--kanban
 * US-040 — Tableau Kanban avec glisser-déposer HTML5 natif (aucune lib / CDN).
 *
 * Câblage Twig :
 *   data-controller="tailsfadmin--kanban"
 *   sur chaque colonne (zone de dépôt) :
 *     data-tailsfadmin--kanban-target="list" data-status="todo" data-label="À faire"
 *     data-action="dragover->…#dragOver drop->…#drop dragenter->…#dragEnter dragleave->…#dragLeave"
 *   sur chaque carte :
 *     draggable="true" data-card-id="…" data-card-title="…"
 *     data-action="dragstart->…#dragStart dragend->…#dragEnd"
 *   compteur par colonne : data-tailsfadmin--kanban-target="count" data-status="todo"
 *   annonceur a11y : data-tailsfadmin--kanban-target="announcer" (role=status, aria-live)
 *   alternative clavier (bouton par colonne cible) :
 *     data-action="…#moveTo" data-status="review"
 *
 * Dépôt hors colonne : la carte reste à sa place (aucune perte, aucune erreur).
 */
export default class extends Controller {
    static targets = ["list", "count", "announcer"];

    connect() {
        this.#updateCounts();
    }

    dragStart(event) {
        this.dragged = event.currentTarget;
        this.dragged.classList.add("opacity-50");
        if (event.dataTransfer) {
            event.dataTransfer.effectAllowed = "move";
            event.dataTransfer.setData("text/plain", this.dragged.dataset.cardId ?? "");
        }
    }

    dragEnd(event) {
        event.currentTarget.classList.remove("opacity-50");
        this.#clearDropHints();
        this.dragged = null;
    }

    dragOver(event) {
        event.preventDefault();
        if (event.dataTransfer) {
            event.dataTransfer.dropEffect = "move";
        }
    }

    dragEnter(event) {
        event.currentTarget.classList.add("ring-2", "ring-brand-400");
    }

    dragLeave(event) {
        event.currentTarget.classList.remove("ring-2", "ring-brand-400");
    }

    drop(event) {
        event.preventDefault();
        const list = event.currentTarget;
        list.classList.remove("ring-2", "ring-brand-400");
        if (!this.dragged) {
            return;
        }
        list.appendChild(this.dragged);
        this.#afterMove(this.dragged, list);
    }

    /** Alternative clavier : déplace la carte vers la colonne ciblée. */
    moveTo(event) {
        const status = event.currentTarget.dataset.status;
        const card = event.currentTarget.closest("[data-card-id]");
        const list = this.listTargets.find((candidate) => candidate.dataset.status === status);
        if (!card || !list) {
            return;
        }
        list.appendChild(card);
        this.#afterMove(card, list);
        card.focus();
    }

    // ─── Helpers privés ──────────────────────────────────────────────────────

    #afterMove(card, list) {
        this.#updateCounts();
        const title = card.dataset.cardTitle ?? "La tâche";
        const label = list.dataset.label ?? list.dataset.status ?? "";
        this.#announce(`${title} déplacée vers ${label}.`);
    }

    #updateCounts() {
        for (const count of this.countTargets) {
            const list = this.listTargets.find(
                (candidate) => candidate.dataset.status === count.dataset.status,
            );
            count.textContent = list ? String(list.querySelectorAll("[data-card-id]").length) : "0";
        }
    }

    #clearDropHints() {
        for (const list of this.listTargets) {
            list.classList.remove("ring-2", "ring-brand-400");
        }
    }

    #announce(message) {
        if (this.hasAnnouncerTarget) {
            this.announcerTarget.textContent = message;
        }
    }
}
