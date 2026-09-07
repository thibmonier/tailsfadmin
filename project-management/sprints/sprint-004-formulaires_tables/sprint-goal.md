# Sprint 004 — Contenu riche : cards, formulaires & tables

## Informations

| Attribut | Valeur |
|----------|--------|
| Numéro | 004 |
| Durée | 2 semaines (10 j) |
| Capacité | ~19-21 pts (1 dev) |
| Engagé | **21 points** |
| Prérequis | Sprints 1-3 ✅ (fondations, navigation, UI Kit) |

## Sprint Goal

> **Permettre de composer des pages admin riches en contenu : cartes & médias, formulaires complets (compatibles Symfony Forms) et tables de données — en Twig Components fidèles à TailAdmin, accessibles et thématisés.**

## Sprint Backlog

| Priorité | ID | Titre | Points | Statut |
|----------|-----|-------|--------|--------|
| 🟡 Should | US-013 | Cards, media cards, grid images, videos (fin EPIC-003) | 5 | 🔵 To Do |
| 🔴 Must | US-014 | Composants de formulaire (inputs, select, checkbox/radio, toggle, textarea, états) | 8 | 🔵 To Do |
| 🟡 Should | US-017 | Tables (basiques + avancées) | 5 | 🔵 To Do |
| — | Garde-fous (smoke CSS + galerie) | 3 | (transverses) | 🔵 |

**Total engagé : 18 pts US + tâches transverses** (≈ dans la vélocité 19-21)

> **US-015 (datepicker) et US-016 (upload)** dépendent d'US-014 → **Sprint 5** (avec EPIC-005 data-viz).

## Ordre de développement recommandé

`US-013 (cards, présentational, rapide) → US-014 (formulaires, le gros morceau, compat Symfony Forms) → US-017 (tables, réutilise dropdown pour actions)`

## Capitalisation Sprints précédents

- **US-017** réutilise le contrôleur `dropdown` (US-007/012) pour les menus d'actions par ligne.
- **US-014** s'appuie sur le CSS de formulaire déjà porté (`@tailwindcss/forms` + classes `.form-check-input` etc. dans `app.css`).
- **Garde-fous en place** : `CssBuildTest` (smoke CSS) et Panther — à étendre aux nouveaux composants si pertinent.

## Definition of Ready (vérifiée)

- [x] US-013/014/017 : description, Gherkin (1 nominal + 2 alt + 2 err), estimation, dépendances.
- [x] Sources : `src/partials/{grid-image,video,media-card}`, `src/form-elements.html`, `src/partials/table/`, Laravel `components/form/*` et `components/tables/basic-tables/*`.
- [x] UI Kit (boutons, badges…) et contrôleurs disponibles.

## Dépendances

| US | Dépend de | Statut |
|----|-----------|--------|
| US-013 | US-002 (CSS), US-004 (layout) | ✅ |
| US-014 | US-010 (buttons) | ✅ |
| US-017 | US-012 (dropdown pour actions) | ✅ |

## Risques

> **Décision d'architecture US-014 (actée)** : **les deux** — composants autonomes `tsf:Form:*` (usage direct dans un template) **ET** un **form theme Symfony** qui les réutilise (`{{ form_row(form.x) }}` rend en style TailAdmin). Priorité aux composants ; le form theme peut déborder en Sprint 5 s'il le faut.

| Risque | Prob. | Impact | Mitigation |
|--------|-------|--------|------------|
| US-014 « les deux » (composants + form theme) volumineux | Moyenne | Moyen | Livrer d'abord les composants autonomes (valeur immédiate), puis le form theme ; découper par famille de champ |
| US-014 volumineuse (8 pts, nombreux champs) | Moyenne | Moyen | Découper par famille de champ ; livrer incrémentalement |
| Régression CSS (formulaires) | Faible | Moyen | `CssBuildTest` + revue visuelle galerie |

## Cérémonies

| Cérémonie | Objet |
|-----------|-------|
| Planning P1 | Sprint Goal + périmètre |
| Planning P2 | Décomposition (`/project:decompose-tasks 004`) |
| Daily | Avancement, blocages |
| Review | Démo : galerie enrichie (cards, form, tables) clair/dark |
| Rétro | Directive Fondamentale |

## Directive Fondamentale de la Rétrospective

> « Indépendamment de ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait le meilleur travail possible, compte tenu de ce qui était connu à ce moment-là, de ses compétences et capacités, des ressources disponibles et de la situation du moment. » — Norman Kerth

---

**Prochaine étape :** `/project:decompose-tasks 004` puis développement TDD.
