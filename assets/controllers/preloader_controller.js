import { Controller } from "@hotwired/stimulus";

/**
 * Stimulus controller — preloader
 *
 * Masque le preloader dès que la page est entièrement chargée (window.load).
 * Remplace l'implémentation Alpine.js originale (x-show / x-init).
 *
 * Nom Stimulus : tailsfadmin--preloader
 * Usage HTML : data-controller="tailsfadmin--preloader"
 */
export default class extends Controller {
    connect() {
        if (document.readyState === "complete") {
            this.#hide();
        } else {
            window.addEventListener("load", () => this.#hide(), { once: true });
        }
    }

    #hide() {
        this.element.style.display = "none";
    }
}
