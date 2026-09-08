/**
 * ESM adapter pour le bundle global FullCalendar v6.1.21 (jsDelivr +esm).
 *
 * Contexte : le bundle jsDelivr exécute un IIFE `(function(V){...})({})` qui
 * peuple `V` avec Calendar, createPlugin, etc., puis marque l'objet via
 * `Object.defineProperty(V, "__esModule", {value: true})`. Mais la valeur de
 * retour de l'IIFE est perdue — aucun export ESM n'est émis par le fichier.
 *
 * Ce wrapper intercepte l'appel `Object.defineProperty` AVANT le chargement
 * dynamique du bundle, ce qui permet de capturer `V` (l'objet FullCalendar
 * entièrement peuplé) et de le ré-exporter comme module ESM standard.
 *
 * Avantage central : l'IIFE est un bundle autonome à portée unique. Il n'y a
 * qu'un seul appel `createContext()` → plus de conflit de contexte Preact entre
 * les bundles jsDelivr séparés (@fullcalendar/core/index.js vs internal.js).
 * Tous les plugins (dayGrid, timeGrid, list, interaction) sont pré-enregistrés.
 */

// Intercepte Object.defineProperty pour capturer l'objet FullCalendar
// au moment où l'IIFE le marque __esModule.
let _fc = null;
const _orig = Object.defineProperty;
Object.defineProperty = function (obj, prop, desc) {
    if (
        prop === '__esModule' &&
        desc != null &&
        desc.value === true &&
        obj != null &&
        typeof obj.Calendar === 'function'
    ) {
        _fc = obj;
        Object.defineProperty = _orig; // restauration immédiate
    }
    return _orig.apply(this, arguments);
};

// Import dynamique : s'exécute APRÈS que l'intercepteur est en place.
// Utilise le spécificateur bare mappé dans l'importmap (pas de chemin relatif
// qui échapperait à la résolution via importmap).
await import('fullcalendar/index.global.min.js');

// Sécurité : restaurer si l'intercepteur n'a pas été déclenché
Object.defineProperty = _orig;

if (!_fc || typeof _fc.Calendar !== 'function') {
    // Fallback vers le global si disponible (chargement <script> externe)
    _fc = globalThis.FullCalendar ?? {};
}

export const Calendar             = _fc.Calendar;
export const createPlugin         = _fc.createPlugin;
export const Draggable            = _fc.Draggable;
export const ThirdPartyDraggable  = _fc.ThirdPartyDraggable;
export const formatDate           = _fc.formatDate;
export const formatRange          = _fc.formatRange;
export const globalLocales        = _fc.globalLocales;
export const globalPlugins        = _fc.globalPlugins;
export const sliceEvents          = _fc.sliceEvents;
export const version              = _fc.version;
export default _fc;
