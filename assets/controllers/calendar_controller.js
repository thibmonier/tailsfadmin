import { Controller } from "@hotwired/stimulus";
import { Calendar } from "@fullcalendar/core/index.js";
import dayGridPlugin    from "@fullcalendar/daygrid";
import timeGridPlugin   from "@fullcalendar/timegrid";
import listPlugin       from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";

/**
 * Contrôleur Stimulus calendar — tailsfadmin--calendar
 *
 * US-020 : encapsule FullCalendar v6 (vendoré via importmap) sans CDN.
 * Pattern wrapper ADR-004 : lib JS tierce gérée dans connect()/disconnect().
 *
 * Valeurs (data-*-value) :
 *   - events      : JSON — tableau d'événements FullCalendar
 *   - initialView : "dayGridMonth" | "timeGridWeek" | "listWeek" (défaut "dayGridMonth")
 *
 * Cibles (data-tailsfadmin--calendar-target) :
 *   - calendarEl  : conteneur dans lequel FullCalendar rend le calendrier
 *   - eventTitle  : élément DOM à remplir avec le titre de l'événement cliqué
 *   - eventStart  : élément DOM à remplir avec la date de début
 *   - eventEnd    : élément DOM à remplir avec la date de fin
 *
 * Modale d'événement : réutilise le contrôleur tailsfadmin--modal (US-011).
 * Lors d'un dateClick ou eventClick, le calendrier :
 *   1. Renseigne les cibles (eventTitle, eventStart, eventEnd).
 *   2. Retrouve le contrôleur modal imbriqué et appelle .open().
 *
 * Dark-mode aware : MutationObserver sur la classe .dark de <html>
 *   → ajoute/retire la classe "fc-dark" sur le conteneur calendarEl.
 *
 * Cycle de vie :
 *   connect()    → instancie Calendar, rend, observe le thème
 *   disconnect() → déconnecte l'observer, détruit l'instance
 */

const DATE_FORMAT_OPTIONS = {
    year:  "numeric",
    month: "long",
    day:   "numeric",
};

/** Formate une date ISO en chaîne lisible. */
function formatDate(dateStr) {
    if (!dateStr) return "—";
    try {
        return new Date(dateStr).toLocaleDateString("fr-FR", DATE_FORMAT_OPTIONS);
    } catch {
        return dateStr;
    }
}

export default class extends Controller {
    static values = {
        events:      { type: String, default: "[]" },
        initialView: { type: String, default: "dayGridMonth" },
    };

    static targets = ["calendarEl", "eventTitle", "eventStart", "eventEnd"];

    connect() {
        let events = [];
        try {
            events = JSON.parse(this.eventsValue);
        } catch (e) {
            console.error("tailsfadmin--calendar: events JSON invalide", e);
        }

        const isDark = document.documentElement.classList.contains("dark");

        this._calendar = new Calendar(this.calendarElTarget, {
            plugins:     [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
            initialView: this.initialViewValue,
            headerToolbar: {
                left:   "prev,next today",
                center: "title",
                right:  "dayGridMonth,timeGridWeek,listWeek",
            },
            selectable:     true,
            events:         events,
            eventColor:     "#465FFF",

            /** Clic sur une date vide → ouvre la modale avec les infos de la date. */
            dateClick: (info) => {
                this.#populateModal("Nouvel événement", info.dateStr, "");
                this.#openModal();
            },

            /** Clic sur un événement existant → ouvre la modale avec ses infos. */
            eventClick: (info) => {
                const evt = info.event;
                if (evt.url) {
                    window.open(evt.url);
                    info.jsEvent.preventDefault();
                    return;
                }
                this.#populateModal(
                    evt.title,
                    evt.startStr,
                    evt.endStr || "",
                );
                this.#openModal();
            },
        });

        this._calendar.render();

        // Dark-mode initial
        if (isDark) {
            this.calendarElTarget.classList.add("fc-dark");
        }

        // Observer les changements de thème
        this._observer = new MutationObserver(() => {
            const dark = document.documentElement.classList.contains("dark");
            this.calendarElTarget.classList.toggle("fc-dark", dark);
        });

        this._observer.observe(document.documentElement, {
            attributes:      true,
            attributeFilter: ["class"],
        });
    }

    disconnect() {
        if (this._observer) {
            this._observer.disconnect();
            this._observer = null;
        }
        if (this._calendar) {
            this._calendar.destroy();
            this._calendar = null;
        }
    }

    /**
     * Remplit les cibles textuelles du contrôleur (dans la modale d'événement).
     */
    #populateModal(title, start, end) {
        if (this.hasEventTitleTarget) {
            this.eventTitleTarget.textContent = title || "Événement";
        }
        if (this.hasEventStartTarget) {
            this.eventStartTarget.textContent = formatDate(start);
        }
        if (this.hasEventEndTarget) {
            this.eventEndTarget.textContent = end ? formatDate(end) : "—";
        }
    }

    /**
     * Retrouve le contrôleur tailsfadmin--modal imbriqué et appelle open().
     * Réutilise le contrôleur US-011 sans modification.
     */
    #openModal() {
        const modalEl = this.element.querySelector('[data-controller*="tailsfadmin--modal"]');
        if (!modalEl) return;

        const modalController = this.application.getControllerForElementAndIdentifier(
            modalEl,
            "tailsfadmin--modal",
        );

        if (modalController) {
            modalController.open();
        }
    }
}
