import { Controller } from "@hotwired/stimulus";
import jsVectorMap from "jsvectormap";
import "jsvectormap/dist/maps/world.js"; // enregistre la carte nommée "world"
import "jsvectormap/dist/jsvectormap.min.css"; // CSS via le pipeline (leçon Sprint 5)

/**
 * Contrôleur Stimulus vectormap — tailsfadmin--vectormap
 *
 * US-019 : encapsule jsvectormap 1.7 (vendoré via importmap, pas de CDN).
 * Pattern wrapper ADR-004 : lib JS tierce gérée dans connect()/disconnect().
 *
 * Valeurs (data-*-value) :
 *   - map             : nom de la carte (défaut "world")
 *   - markers         : JSON — [{ name, coords: [lat, lng] }]
 *   - backgroundColor : fond du conteneur SVG (défaut "transparent")
 *   - regionColor     : couleur de remplissage initiale des régions
 *
 * Dark-mode : les couleurs des régions/marqueurs sont pilotées par les classes
 * `.jvm-*` de assets/styles/app.css (avec variantes dark:), donc le basculement
 * de thème est automatique côté CSS. L'observer ne sert qu'à rafraîchir la taille.
 */
const BRAND = "#465FFF";

export default class extends Controller {
    static values = {
        map: { type: String, default: "world" },
        markers: { type: String, default: "[]" },
        backgroundColor: { type: String, default: "transparent" },
        regionColor: { type: String, default: "#D0D5DD" },
    };

    static targets = ["mapContainer"];

    connect() {
        let markers = [];

        try {
            markers = JSON.parse(this.markersValue);
        } catch (e) {
            console.warn("tailsfadmin--vectormap: markers JSON invalide", e);
        }

        const el = this.hasMapContainerTarget ? this.mapContainerTarget : this.element;

        try {
            this._map = new jsVectorMap({
                selector: el,
                map: this.mapValue,
                zoomButtons: false,
                backgroundColor: this.backgroundColorValue,
                regionStyle: {
                    initial: { fontFamily: "Outfit", fill: this.regionColorValue },
                    hover: { fillOpacity: 1, fill: BRAND },
                },
                markers,
                markerStyle: {
                    initial: { strokeWidth: 1, fill: BRAND, fillOpacity: 1, r: 4 },
                    hover: { fill: BRAND, fillOpacity: 1 },
                    selected: {},
                    selectedHover: {},
                },
            });
        } catch (e) {
            console.error("tailsfadmin--vectormap: échec d'initialisation", e);
            el.textContent = "Carte indisponible";
            return;
        }

        // Rafraîchit la taille au changement de thème (le style est géré par le CSS).
        this._observer = new MutationObserver(() => {
            if (this._map && typeof this._map.updateSize === "function") {
                this._map.updateSize();
            }
        });
        this._observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ["class"],
        });
    }

    disconnect() {
        if (this._observer) {
            this._observer.disconnect();
            this._observer = null;
        }
        if (this._map) {
            this._map.destroy();
            this._map = null;
        }
    }
}
