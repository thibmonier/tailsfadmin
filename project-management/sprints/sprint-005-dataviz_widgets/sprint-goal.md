# Sprint 005 — Data-viz & widgets riches (intégrations JS)

## Informations

| Attribut | Valeur |
|----------|--------|
| Numéro | 005 |
| Durée | 2 semaines (10 j) |
| Capacité | ~19-24 pts (1 dev) |
| Engagé | **24 points** |
| Prérequis | Sprints 1-4 ✅ |

## Sprint Goal

> **Intégrer les librairies JS riches de TailAdmin — graphiques, carte, calendrier, datepicker, upload — en les encapsulant dans des contrôleurs Stimulus réutilisables, vendorés via importmap (sans CDN runtime) et « dark-mode aware ».**

C'est le sprint qui valide le **pattern wrapper** de l'ADR-004 sur de vraies libs.

## Sprint Backlog

| Priorité | ID | Titre | Points | Lib | Statut |
|----------|-----|-------|--------|-----|--------|
| 🔴 Must | US-018 | Graphiques ApexCharts (line/bar/dashboard) | 8 | ApexCharts | 🔵 To Do |
| 🟡 Should | US-015 | Datepicker (flatpickr) | 3 | flatpickr | 🔵 To Do |
| 🟡 Should | US-016 | Upload (Dropzone) | 5 | Dropzone | 🔵 To Do |
| 🟡 Should | US-020 | Calendrier FullCalendar + modal d'événement | 8 | FullCalendar | 🔵 To Do |

**Total engagé : 24 points**

> **US-019 (carte jsvectormap, Could, 5 pts)** : gardée en réserve (1re à sortir si le sprint déborde ; les charts/calendrier sont prioritaires).

## Ordre de développement recommandé

`US-015 (flatpickr, petit, valide le pattern wrapper) → US-016 (Dropzone) → US-018 (ApexCharts, gros) → US-020 (FullCalendar + modal existant)`

## Décision d'intégration (ADR-004, rappel)

Chaque lib est **vendorée via `importmap:require <lib>`** (provenance jsDelivr, pas de CDN runtime) et **encapsulée dans un contrôleur Stimulus** : montage sur `connect()`, destruction sur `disconnect()`, données via `values`/`targets`, **dark-mode aware** (observation de la classe `.dark` sur `<html>`). Wrappers maison (pas de package UX officiel pour ApexCharts/FullCalendar).

## Definition of Ready (vérifiée)

- [x] US-015/016/018/020 : description, Gherkin, estimation, dépendances.
- [x] Sources : `src/js/components/charts/chart-01..03.js`, `calendar-init.js`, `index.js` (flatpickr/Dropzone init), `src/partials/calendar-event-modal.html`.
- [x] importmap opérationnel (Sprint 1) ; contrôleur `modal` (US-011) pour l'événement calendrier ; composants form (US-014) pour datepicker.

## Dépendances

| US | Dépend de | Statut |
|----|-----------|--------|
| US-018 | US-002 (importmap/Stimulus) | ✅ |
| US-015 | US-014 (composants form) | ✅ |
| US-016 | US-014 | ✅ |
| US-020 | US-011 (modal pour création/édition d'événement) | ✅ |

## Risques

| Risque | Prob. | Impact | Mitigation |
|--------|-------|--------|------------|
| Lib JS non vendorable proprement via importmap (deps/CSS) | Moyenne | Moyen | Tester `importmap:require` tôt ; gérer le CSS des libs (flatpickr/FullCalendar) via AssetMapper ou @import |
| Comportement JS runtime non couvert par tests fonctionnels | Moyenne | Moyen | Étendre Panther (garde-fou Sprint 3) à 1-2 parcours (ex. chart monté, datepicker ouvert) |
| CSP vs styles inline des libs (ApexCharts) | Moyenne | Faible | Tester la CSP réelle (security.md) |
| US-018 + US-020 volumineux (8+8) | Moyenne | Moyen | Datepicker/upload d'abord (valident le pattern), charts/calendrier ensuite ; US-019 en réserve |

## Cérémonies

| Cérémonie | Objet |
|-----------|-------|
| Planning P1 | Sprint Goal + périmètre |
| Planning P2 | Décomposition (`/project:decompose-tasks 005`) |
| Daily | Avancement, blocages (vendoring libs) |
| Review | Démo : dashboard avec charts + calendrier + datepicker/upload |
| Rétro | Directive Fondamentale |

## Directive Fondamentale de la Rétrospective

> « Indépendamment de ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait le meilleur travail possible, compte tenu de ce qui était connu à ce moment-là, de ses compétences et capacités, des ressources disponibles et de la situation du moment. » — Norman Kerth

---

**Prochaine étape :** `/project:decompose-tasks 005` puis développement TDD.
