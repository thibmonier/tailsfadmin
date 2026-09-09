# Sprint 009 — Bibliothèque de pages d'exemples (dashboards & pages type)

## Sprint Goal

> **Élargir le catalogue de pages prêtes à l'emploi : quatre dashboards métier
> (Analytics, Marketing, CRM, SaaS) et six pages type applicatives (Settings,
> Pricing, Invoice, Chat, File manager, Inbox), assemblées depuis le bundle,
> fidèles TailAdmin, clair/dark, accessibles (WCAG AA), responsive — sans CDN.**

## Périmètre

- **EPIC** : EPIC-009 — Bibliothèque de pages d'exemples
- **User Stories** : US-032, US-033 (les « Should »). US-034 + US-035 (« Could ») → **Sprint 010**.
- **Points engagés** : 16 (dans la vélocité 20-40)
- **Durée** : 2 semaines
- **Version cible** : v1.4.0

| US | Titre | Points | Priorité |
|----|-------|--------|----------|
| US-032 | Dashboards Analytics / Marketing / CRM / SaaS | 8 | Should |
| US-033 | Pages type : Settings, Pricing, Invoice, Chat, File manager, Inbox | 8 | Should |

## Ordre recommandé

`US-032 (dashboards — assemblage) → US-033 (pages type)`

Les deux US sont de l'**assemblage** de composants déjà livrés (charts, tables,
KPI, Tabs, Modal, Dropzone, Badge, ProgressBar, Ribbon). US-032 en premier car
plus homogène (4 pages de même nature) et prérequis d'US-035 (scaffolding, Sprint 10).

## Contraintes techniques

- **Bundle Symfony + démo** : pages assemblées dans `demo/`, composants `tsf:` +
  contrôleurs Stimulus existants. Données de démo **statiques** (ADR-002).
- **Sans CDN** (ADR-004/006) : charts via le contrôleur `tailsfadmin--apexcharts`
  déjà vendoré ; aucune nouvelle lib tierce attendue.
- **Réutilisation (règle des 3)** : n'extraire un nouveau composant bundle que si
  un motif se répète sur ≥ 3 pages. Kanban **déjà livré** (US-040), Tabs (US-036).
- **Accessibilité** : rendu clair/dark sans inversion des gris (garde v1), 0 violation
  axe A/AA, navigation clavier, revue visuelle systématique (P-002).

## Cérémonies

- **Planning (Part 1 & 2)** : périmètre ci-dessus + décomposition (`tasks/`).
- **Daily** : suivi via `task-board.md`.
- **Review** : démo des 4 dashboards + 6 pages type sur routes dédiées.
- **Rétrospective** : Directive Fondamentale — « Quoi qu'on découvre, chacun a fait
  de son mieux compte tenu de ce qu'il savait, de ses compétences et des moyens
  disponibles. »
- **Affinage** : préparer Sprint 010 (US-034 auth/utilitaires, US-035 scaffolding).

## Definition of Done

Voir `project-management/definition-of-done.md`. Rappels : chaque page étend le layout
du bundle (non-duplication), tests fonctionnels (200 + structure), revue visuelle
clair/dark, PHPStan max, php-cs-fixer, Biome (si contrôleur), CI 6 jobs verte.
