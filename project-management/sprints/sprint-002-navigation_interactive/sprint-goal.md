# Sprint 002 — Navigation & layout interactifs

## Informations

| Attribut | Valeur |
|----------|--------|
| Numéro | 002 |
| Durée | 2 semaines (10 j) |
| Capacité | ~21-26 pts (1 dev) |
| Engagé | **21 points** |
| Prérequis | Sprint 1 (Walking Skeleton) ✅ livré |

## Sprint Goal

> **Transformer le layout statique du Walking Skeleton en une navigation pleinement interactive : dark mode persistant, sidebar responsable multi-niveaux et header fonctionnel — le tout en Stimulus/UX, sans Alpine.js.**

On capitalise sur le contrôleur Stimulus déjà distribué depuis le bundle (US-004) et le système de tokens dark (US-002).

## Sprint Backlog

| Priorité | ID | Titre | Points | Statut |
|----------|-----|-------|--------|--------|
| 🔴 Must | US-005 | Dark mode persistant (Stimulus) | 5 | 🔵 To Do |
| 🔴 Must | US-006 | Sidebar responsive multi-niveaux (collapse/mobile, item actif) | 8 | 🔵 To Do |
| 🟡 Should | US-007 | Header (recherche Cmd/Ctrl+K, dropdowns, breadcrumb) | 8 | 🔵 To Do |

**Total engagé : 21 points**

## Ordre de développement recommandé

`US-005 (rapide, débloque le toggle déjà réservé) → US-006 (sidebar, cœur nav) → US-007 (header)`

## Definition of Ready (vérifiée)

- [x] US-005/006/007 : description claire, Gherkin (1 nominal + 2 alt + 2 err), estimation, dépendances.
- [x] Sources de référence disponibles (`Tools/sources/…` restaurées).
- [x] Layout et contrôleur Stimulus du bundle opérationnels (Sprint 1).

## Dépendances

| US | Dépend de | Statut |
|----|-----------|--------|
| US-005 | US-004 (bouton toggle réservé dans le header) | ✅ |
| US-006 | US-004 (sidebar statique), `MenuBuilder` à créer | ✅ / à faire |
| US-007 | US-004 (header), contrôleur `dropdown` à créer | ✅ / à faire |

## Actions de rétro (Sprint 1) à intégrer

- [ ] **Action 1** : script `composer test` (build → compile → phpunit) — évite le footgun assets.
- [ ] **Action 2** : corriger la doc « Pest 4 / PHPUnit 12 » → acter PHPUnit 12.
- [ ] **Action 3** : documenter le caveat `asset_mapper.yaml` (rattaché à US-026, plus tard).

## Risques

| Risque | Prob. | Impact | Mitigation |
|--------|-------|--------|------------|
| Conversion Alpine→Stimulus non triviale (sidebar/dropdowns : focus trap, clavier, ARIA) | Moyenne | Moyen | Contrôleurs dédiés testés ; réutiliser les patterns du POC preloader |
| FOUC dark mode | Moyenne | Faible | Script inline anti-FOUC (< 200 o) prévu en US-005 |
| Combinatoire dark × RTL sur la sidebar | Faible | Moyen | Poser les classes `ltr:`/`rtl:` dès US-006 |

## Cérémonies

| Cérémonie | Objet |
|-----------|-------|
| Planning P1 | Sprint Goal + périmètre (ci-dessus) |
| Planning P2 | Décomposition en tâches (`/project:decompose-tasks 002`) |
| Daily | Avancement, blocages |
| Review | Démo : dark mode + sidebar + header interactifs |
| Rétro | Directive Fondamentale |

## Directive Fondamentale de la Rétrospective

> « Indépendamment de ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait le meilleur travail possible, compte tenu de ce qui était connu à ce moment-là, de ses compétences et capacités, des ressources disponibles et de la situation du moment. » — Norman Kerth

---

**Prochaine étape :** `/project:decompose-tasks 002` puis développement TDD (`/sprint:dev US-005`).
