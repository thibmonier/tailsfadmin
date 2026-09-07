import { Controller } from "@hotwired/stimulus";

/**
 * Contrôleur Stimulus : tailsfadmin--alert-dismiss
 * US-008 — Fermeture d'une alerte (composant tsf:Ui:Alert dismissible).
 *
 * Câblage Twig :
 *   data-controller="tailsfadmin--alert-dismiss"
 *   data-action="click->tailsfadmin--alert-dismiss#dismiss"
 *
 * Remplace l'implémentation Alpine.js originale (ADR-004).
 */
export default class extends Controller {
    /**
     * Supprime l'élément hôte du DOM pour masquer l'alerte.
     * Aucune animation pour garder le contrôleur minimal (YAGNI).
     */
    dismiss() {
        this.element.remove();
    }
}
