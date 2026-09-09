# Sprint 011 — Composants d'affichage & pages d'exemple avancées

## Sprint Goal

> **Étendre le thème de trois composants d'affichage réutilisables (Tabs, Progress
> bars, Ribbons) et d'un jeu de pages d'exemple avancées (six layouts, form layout,
> Integrations/API keys, task list + Kanban), tous assemblés depuis le bundle,
> fidèles TailAdmin, clair/dark, accessibles (WCAG AA), responsive — sans CDN.**

## Périmètre

- **EPIC** : EPIC-010 — Composants d'affichage & pages d'exemple avancées
- **User Stories** : US-036, US-037, US-038, US-039, US-040
- **Points engagés** : 32 (dans la vélocité 20-40)
- **Durée** : 2 semaines
- **Version cible** : v1.3.0 (v1.2.0 déjà publiée par un autre lot — menu/header)

| US | Titre | Points | Priorité |
|----|-------|--------|----------|
| US-036 | Composants : Tabs, Progress bars, Ribbons | 8 | Could |
| US-037 | 6 layouts d'exemple (variantes de shell) | 8 | Could |
| US-038 | Page Form Layout | 3 | Could |
| US-039 | Page Integrations / API keys | 5 | Could |
| US-040 | Task list : liste + Kanban | 8 | Could |

## Ordre recommandé

`US-036 (composants — enabler) → US-037 / US-038 / US-039 / US-040 (pages qui réutilisent les composants)`

Les composants d'US-036 sont un **prérequis** des pages : les développer en premier
débloque le reste du sprint.

## Contraintes techniques

- **Bundle Symfony + démo** : pas de [DB]/[API]/[FE-MOB] — composants Twig (`tsf:`),
  contrôleurs Stimulus (`tailsfadmin--…`), pages assemblées dans la démo.
- **Sans CDN** (ADR-004/006) : toute interactivité repose sur des contrôleurs
  Stimulus natifs (tabs, clipboard, kanban) — aucun vendoring de lib tierce requis
  pour ce sprint.
- **Accessibilité** : patterns ARIA (Tabs, progressbar, aria-live pour le Kanban),
  focus visible, alternatives clavier au drag-and-drop.
- **Réutilisation** (règle des 3) : réemployer Card/Table/Modal/Badge/Dropdown ;
  nouveaux composants bundle limités à Tabs/Progress/Ribbons.

## Cérémonies

- **Planning (Part 1 & 2)** : périmètre ci-dessus + décomposition (ce dossier `tasks/`).
- **Daily** : suivi via `task-board.md`.
- **Review** : démo des 3 composants + pages sur `/ui-kit` et routes dédiées.
- **Rétrospective** : Directive Fondamentale — « Quoi qu'on découvre, chacun a fait
  de son mieux compte tenu de ce qu'il savait, de ses compétences et des moyens
  disponibles. »
- **Affinage** : trancher le chevauchement Kanban (US-040 ↔ US-033) et l'éventuelle
  scission des US à 8 pts (US-036/037/040).

## Definition of Done

Voir `project-management/definition-of-done.md`. Rappels clés : composants documentés
(`docs/components.md`), revue visuelle clair/dark (P-002), tests fonctionnels +
E2E Panther pour l'interactivité, PHPStan niveau max, php-cs-fixer, Biome sur les
contrôleurs, CI verte (dont job démo/e2e).
