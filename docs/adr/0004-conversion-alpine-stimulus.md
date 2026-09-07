# ADR-004 : Conversion Alpine.js → Stimulus & encapsulation des librairies JS

## Statut

**Accepté** (2026-09-07)

## Contexte

Les sources TailAdmin portent leur interactivité en **Alpine.js** (`x-data`, `x-show`, `@click`, plugin `persist`) et initialisent des librairies tierces (ApexCharts, FullCalendar, flatpickr, Dropzone, jsvectormap, Swiper) dans un `index.js` global. La décision d'init impose la **conversion vers Stimulus / Symfony UX** (0 Alpine.js résiduel), avec chargement via **importmap**.

## Décision

### 1. Comportements d'UI → contrôleurs Stimulus dédiés
Chaque comportement Alpine devient un **contrôleur Stimulus** du bundle, avec `targets`, `values`, `classes` :
- `theme` (dark mode, persistance `localStorage` en remplacement de `@alpinejs/persist`),
- `sidebar` (collapse desktop + drawer mobile + overlay),
- `dropdown` (ouverture, navigation clavier, ARIA, fermeture clic extérieur/Échap),
- `modal` (focus trap, Échap, `aria-modal`),
- `alert-dismiss` (fermeture d'alerte).

### 2. Librairies riches → contrôleurs « wrappers »
Chaque lib est **encapsulée dans un contrôleur Stimulus** (montage sur `connect()`, destruction sur `disconnect()`, données via `values`/`targets`, **dark-mode aware** via observation de la classe du `<html>`) :
`apexcharts`, `fullcalendar`, `flatpickr`, `dropzone`, `vectormap` (jsvectormap), et `swiper` (si porté).
Les libs sont **vendorées** via `importmap:require <lib>` (provenance jsDelivr), pas de CDN runtime.

### 3. Packages UX officiels : au cas par cas
- **Préférer un package UX officiel** lorsqu'il existe et couvre le besoin (ex. `symfony/ux-chartjs` pour Chart.js).
- **Mais** TailAdmin utilise **ApexCharts** (pas Chart.js) et **FullCalendar**, **sans** package UX officiel équivalent → **wrapper Stimulus maison** pour rester fidèle aux sources. On n'introduit pas Chart.js juste pour utiliser un package officiel (fidélité TailAdmin > commodité).

### 4. Twig Components vs Live Components
- **Twig Components (rendu serveur, présentational)** par défaut pour tout le catalogue.
- **Live Components** réservés aux rares cas nécessitant un aller-retour serveur (non requis par le périmètre actuel ; réservé à d'éventuelles démos data-driven futures). YAGNI.

## Alternatives considérées

| Option | ✅ | ❌ |
|--------|----|----|
| **Conversion complète Stimulus (choisi)** | Idiomatique Symfony UX, un seul runtime, testable, réutilisable | Coût de portage (surtout sidebar/modals/dropdowns) |
| Garder Alpine.js | Rapide, proche des sources | ❌ Double runtime, non idiomatique, contraire à la décision d'init |
| Hybride Alpine + Stimulus | Portage réduit | ❌ Deux modèles mentaux, dette, cohérence a11y difficile |

## Conséquences

### Positives
- Interactivité unifiée, testable, accessible (clavier/ARIA centralisés dans les contrôleurs).
- Wrappers de libs **réutilisables** et paramétrables, dark-mode aware.
- Aucun CDN runtime (assets vendorés → CSP/offline).

### Négatives / vigilance
- Le portage **sidebar (US-006)**, **modals (US-011)**, **dropdowns (US-012)** est non trivial (focus trap, clavier) → estimé en conséquence.
- Prévoir un **contrôleur de base** ou des utilitaires partagés (gestion du dark-mode observer) pour éviter la duplication (DRY).

## Références

- [StimulusBundle Documentation](https://symfony.com/bundles/StimulusBundle/current/index.html)
- [Symfony UX](https://ux.symfony.com/)
- [AssetMapper — importmap:require (Symfony Docs)](https://symfony.com/doc/current/frontend/asset_mapper.html)
