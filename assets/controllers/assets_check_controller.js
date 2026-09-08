import { Controller } from "@hotwired/stimulus";

/**
 * Libs tierces du bundle mappées à leur contrôleur Stimulus. Une lib n'est
 * vérifiée que si un élément de la page référence son contrôleur.
 */
const LIBS = [
    { specifier: "apexcharts", controller: "tailsfadmin--apexcharts" },
    { specifier: "flatpickr", controller: "tailsfadmin--datepicker" },
    { specifier: "dropzone", controller: "tailsfadmin--dropzone" },
    { specifier: "jsvectormap", controller: "tailsfadmin--vectormap" },
    { specifier: "fullcalendar", controller: "tailsfadmin--calendar" },
];

/**
 * Préflight des dépendances JS du bundle (US-027 / T-027-04, ADR-007).
 *
 * Quand une page charge un composant qui dépend d'une lib tierce non installée
 * dans l'importmap de l'hôte, le navigateur émet une erreur cryptique
 * (« Failed to resolve module specifier … »). Ce contrôleur ajoute une erreur
 * console EXPLICITE nommant la commande à exécuter.
 *
 * Non-invasif : il ne modifie pas les contrôleurs de lib (imports statiques
 * inchangés → aucun risque de régression). Il n'avertit QUE si un composant de
 * la page dépend réellement de la lib manquante (fidèle au scénario Gherkin).
 *
 * S'attache au <body> du layout admin ; lit le JSON de l'importmap rendu dans le
 * DOM (`<script type="importmap">`) pour savoir si un specifier est déclaré —
 * déterministe et portable, contrairement à `import.meta.resolve` (indisponible
 * dans certains contextes). Dégrade en no-op si aucun importmap n'est présent.
 */
export default class extends Controller {
    connect() {
        const imports = this.#importmapImports();
        if (imports === null) {
            return;
        }

        const missing = LIBS.filter(({ specifier, controller }) => {
            if (!document.querySelector(`[data-controller~="${controller}"]`)) {
                return false;
            }
            return !(specifier in imports);
        }).map(({ specifier }) => specifier);

        if (missing.length > 0) {
            console.error(
                `[tailsfadmin] Bibliothèque(s) JS non installée(s) : ${missing.join(", ")}. ` +
                    "Exécutez « php bin/console tailsfadmin:assets:install » " +
                    "pour les ajouter à l'importmap (vendoring local, sans CDN).",
            );
            // Marqueur DOM (en plus de l'erreur console) : permet à un outil ou à
            // un test d'intégration (US-030) de détecter l'absence de vendoring de
            // façon déterministe, sans dépendre de la capture des logs navigateur.
            document.documentElement.dataset.tailsfadminMissingLibs = missing.join(",");
        }
    }

    /**
     * Table `imports` de l'importmap rendue par AssetMapper, ou null si absente
     * ou illisible (dégradation en no-op).
     */
    #importmapImports() {
        const script = document.querySelector('script[type="importmap"]');
        if (!script) {
            return null;
        }
        try {
            return JSON.parse(script.textContent).imports ?? {};
        } catch {
            return null;
        }
    }
}
