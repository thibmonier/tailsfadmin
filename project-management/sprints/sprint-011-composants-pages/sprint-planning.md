# Sprint Planning — Sprint 011 (Composants & pages, EPIC-010)

## Informations

| Attribut | Valeur |
|----------|--------|
| Numéro | 011 |
| Objectif | Composants d'affichage & pages d'exemple avancées (EPIC-010) |
| Début | 2026-09-09 |
| Fin | 2026-09-22 (10 jours ouvrés) |
| Durée | 2 semaines |
| Version cible | **v1.3.0** (v1.2.0 déjà publiée par un autre lot) |
| Points engagés | 32 (⚠ voir capacité) |

## Prérequis de démarrage

| Prérequis | État |
|-----------|------|
| Sprint précédent clôturé | ✅ Sprint 8 livré (v1.1.0) ; dernière version publiée **v1.2.0** (menu/header, hors sprint tracké) |
| Rétro précédente | ✅ Sprint 8 (gardes reprises : no-CDN, synchro package.json, dark sans inversion des gris) |
| Backlog priorisé (PO) | ✅ EPIC-010 cadré, US-036→040 |
| US « Ready » (gate INVEST) | ✅ 6/6 pour les 5 US (`/gate:validate-backlog`) |
| US estimées + AC Gherkin | ✅ 3C + Gherkin (nominal/alternatifs/erreurs) |
| Décomposition en tâches | ✅ 37 tâches / 79.5h (`tasks/`) |
| Dépendances identifiées | ✅ US-036 enabler ; chevauchement Kanban US-033 à trancher |

> **Note d'ordre** : EPIC-009 (Should, sprints 9-10) est **différé volontairement** ;
> le sprint 011 traite EPIC-010 (Could) en avance de phase, à la demande du PO.

## Capacité & vélocité

Projet **solo** (1 développeur, assisté IA) — la capacité s'exprime en points via la
vélocité, pas en jours-homme théoriques.

| Sprint | Points livrés |
|--------|---------------|
| Sprints v1 (moy. 7 sprints) | ~20 |
| Sprint 8 (distribution) | 26 |
| **Vélocité de référence** | **~24–26** |

- **Capacité recommandée** : ~26 points (haut de la fourchette récente).
- **Engagé** : **32 points** → **au-dessus** de la vélocité de référence.

> ⚠️ **Sur-engagement probable (~6 pts).** 32 pts reste dans la fourchette projet
> (20-40) mais dépasse la moyenne récente. Recommandation : désigner **US-037
> (8 pts, layouts, Could)** comme **variable d'ajustement** — la sortir en premier
> si la mi-sprint montre un retard. Cœur incompressible : US-036 (enabler) +
> US-038 + US-039 + US-040 = 24 pts.

## Definition of Ready (par US)

| US | Description claire | AC Gherkin | Estimée | Déps identifiées | Maquette (réf.) | Ready |
|----|:--:|:--:|:--:|:--:|:--:|:--:|
| US-036 | ✅ | ✅ (6) | ✅ 8 | ✅ | TailAdmin /tabs,/progress-bar,/ribbons | ✅ |
| US-037 | ✅ | ✅ (6) | ✅ 8 | ✅ | TailAdmin /layout-one…six | ✅ |
| US-038 | ✅ | ✅ (6) | ✅ 3 | ✅ | TailAdmin /form-layout | ✅ |
| US-039 | ✅ | ✅ (6) | ✅ 5 | ✅ | TailAdmin /api-keys | ✅ |
| US-040 | ✅ | ✅ (6) | ✅ 8 | ✅ (chevauchement US-033) | TailAdmin /sales,/task-kanban | ✅ |

## Ordre d'exécution

`US-036 (composants, enabler) → US-037 / US-038 / US-039 / US-040 (parallélisables)`

## Cérémonies (adaptées solo / AI-assisted)

| Cérémonie | Quand | Support |
|-----------|-------|---------|
| Planning P1 (Quoi) | 2026-09-09 | `sprint-goal.md` |
| Planning P2 (Comment) | 2026-09-09 | `tasks/` (décomposition) |
| Daily (auto-checkpoint) | quotidien | `daily-notes/` |
| Affinage | mi-sprint | trancher Kanban US-033 + scission éventuelle US-036/037/040 |
| Review | 2026-09-22 | `sprint-review.md` (à créer en fin) |
| Rétrospective | 2026-09-22 | `sprint-retro.md` (à créer en fin) |

## Risques

| Risque | Prob. | Impact | Mitigation |
|--------|-------|--------|------------|
| Sur-engagement (32 > ~26) | Moyenne | Moyen | US-037 = variable d'ajustement (sortir en 1er) |
| A11y du drag-and-drop (Kanban) | Moyenne | Élevé | Alternative clavier + `aria-live` dès la conception (T-040-04) |
| Doublon Kanban US-033 | Moyenne | Faible | Arbitrage en affinage avant de coder US-040 |
| Régression synchro package.json | Faible | Moyen | T-TECH-02 + `AssetsWiringTest` (job e2e) |

## Burndown (prévisionnel)

```
Points |
  32   |●
  28   |    ●
  24   |        ●         (US-036 livré ~ ici)
  20   |            ●
  16   |                ●
  12   |                    ●
   8   |                        ●
   4   |                            ●
   0   |________________________________●
       J1  J2  J3  J4  J5  J6  J7  J8  J9  J10
```

## Notes
- Aucune dépendance JS tierce / CDN dans ce sprint (Tabs, clipboard, Kanban natifs).
- DoD : voir `project-management/definition-of-done.md` (CI verte, PHPStan max, Biome,
  revue visuelle clair/dark, tests fonctionnels + E2E).
