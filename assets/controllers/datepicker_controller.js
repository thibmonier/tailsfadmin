import { Controller } from "@hotwired/stimulus";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

/**
 * Contrôleur Stimulus datepicker — tailsfadmin--datepicker
 *
 * US-015 : encapsule flatpickr (vendoré via importmap) sans CDN.
 * Pattern wrapper ADR-004 : lib JS tierce gérée dans connect()/disconnect().
 *
 * Valeurs (data-*-value) :
 *   - mode          : "single" (défaut) | "range" | "multiple"
 *   - dateFormat    : format de date flatpickr, ex. "Y-m-d" (défaut)
 *   - enableTime    : booléen, affiche le sélecteur d'heure (défaut false)
 *
 * Dark-mode : détecte la classe `.dark` sur <html> au moment du connect()
 * et ajoute la classe `flatpickr-dark` sur le calendrier.
 *
 * Cycle de vie :
 *   connect()    → instancie flatpickr, stocke dans this._fp
 *   disconnect() → détruit l'instance (pas de fuite mémoire)
 */
export default class extends Controller {
    static values = {
        mode: { type: String, default: "single" },
        dateFormat: { type: String, default: "Y-m-d" },
        enableTime: { type: Boolean, default: false },
    };

    connect() {
        const isDark = document.documentElement.classList.contains("dark");

        this._fp = flatpickr(this.element, {
            mode: this.modeValue,
            dateFormat: this.dateFormatValue,
            enableTime: this.enableTimeValue,
            // Injectée après ouverture pour supporter le dark mode
            onReady: (_selectedDates, _dateStr, instance) => {
                if (isDark) {
                    instance.calendarContainer.classList.add("flatpickr-dark");
                }
            },
            // Mise à jour du thème si le mode dark change pendant l'usage
            onChange: (_selectedDates, _dateStr, instance) => {
                const dark = document.documentElement.classList.contains("dark");
                if (dark) {
                    instance.calendarContainer.classList.add("flatpickr-dark");
                } else {
                    instance.calendarContainer.classList.remove("flatpickr-dark");
                }
            },
        });
    }

    disconnect() {
        if (this._fp) {
            this._fp.destroy();
            this._fp = null;
        }
    }
}
