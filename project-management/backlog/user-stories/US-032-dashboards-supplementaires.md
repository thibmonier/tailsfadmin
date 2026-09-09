# US-032 — Dashboards supplémentaires (Analytics, Marketing, CRM, SaaS)

**EPIC :** EPIC-009-pages-exemples · **Statut :** 🟡 In Progress · **Points :** 8 · **Priorité :** Should · **Sprint :** Sprint 9

## Carte (Card)
> En tant que **P-004 — Utilisateur admin**, je veux **plusieurs tableaux de bord métier (Analytics, Marketing, CRM, SaaS)**, afin de **disposer de points de départ variés selon le domaine de mon application**.

## Conversation
Assemblage pur (comme US-021) à partir des composants existants : KPI, charts
(line/bar/area/radial), tables, listes, barres de progression, cartes. Chaque
dashboard illustre un domaine : **Analytics** (sessions, sources, funnel),
**Marketing** (campagnes, ROI, audience), **CRM** (pipeline, deals, activités),
**SaaS** (MRR, churn, cohortes). Réutiliser au maximum ; n'ajouter un composant
bundle que si un motif se répète (règle des 3). Clair+dark, responsive, accessible
(WCAG AA), revue visuelle systématique. Données de démo statiques (ADR-002).

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Dashboards métier
  Scenario: Chaque dashboard s'affiche et est interactif
    Given l'utilisateur ouvre l'un des dashboards (analytics|marketing|crm|saas)
    Then la page retourne 200 et assemble des composants tsf (charts, tables, KPI)
    And les graphiques montent réellement au navigateur (SVG)
    And le rendu est correct en clair ET dark, 0 violation axe A/AA

  Scenario: Aucune duplication de logique réutilisable
    Given un motif d'UI apparaît sur ≥ 3 dashboards
    Then il est extrait en composant bundle plutôt que dupliqué
```

## INVEST
- **Independent** : chaque dashboard est une page autonome.
- **Negotiable** : liste exacte des 4 dashboards ajustable.
- **Valuable** : élargit fortement l'attrait du thème.
- **Estimable** : 4 pages d'assemblage + tests + revue — 8 pts.
- **Small** : assemblage, peu/pas de nouveaux composants.
- **Testable** : tests fonctionnels + E2E montage + axe.

## Dépendances
- **Dépend de :** EPIC-008 (consommabilité), composants existants (charts/tables/cards).
- **Bloque :** US-035 (scaffolding s'appuie sur les gabarits de pages).

## Definition of Done
Voir `project-management/definition-of-done.md`.
