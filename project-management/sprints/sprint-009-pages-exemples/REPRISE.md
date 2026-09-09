# 🔖 Reprise — Sprint 009 (à lire en premier après un /clear)

> Point de reprise du **2026-09-09**. Reprendre EPIC-009 (bibliothèque de pages)
> sans reconstituer le contexte.

## ✅ EPIC-009 CLÔTURÉ (Sprints 9 + 10, 26/26 pts)

**Sprint 9 (16 pts)**
- **US-032 (dashboards) ✅** — PR #27 : `/dashboards` + Analytics/Marketing/CRM/SaaS.
- **US-033 (pages type) ✅** — PR #28 : `/app/{settings,pricing,invoice,chat,files,inbox}`.

**Sprint 10 (10 pts)**
- **US-034 (auth/utilitaires étendus) ✅** — PR #30 : `/auth/{reset-password,new-password,otp,success,maintenance,coming-soon,500}` + contrôleur `tailsfadmin--otp` + page 500 métier.
- **US-035 (scaffolding) ✅** — PR #31 : commande `make:tailsfadmin-page` (blank/dashboard/table/form).

Bonus livré pendant ce lot : `tsf:Ui:Tabs` variante `segmented` + icônes (PR #26).

**Tous les EPICs (001→010) sont terminés.** Prochaine étape : **release v1.4.0** (basculer `[Unreleased]`→`[1.4.0]` dans CHANGELOG via PR, puis `git tag -a v1.4.0` sur le HEAD vert de `main` et push → auto-update Packagist).

## Contexte

- **Sprint 11 (EPIC-010) clôturé, taggé v1.3.0** sur Packagist (composants d'affichage
  + pages avancées : Tabs/ProgressBar/Ribbon, `/layouts`, `/forms/layout`,
  `/integrations`, `/tasks` + `/tasks/kanban`).
- **Reliquat = EPIC-009** « Bibliothèque de pages d'exemples » (US-032→035, 26 pts) —
  jamais démarré. Il n'existait pas de dossier `sprint-009` ni `sprint-010` : ce dossier
  formalise le **Sprint 9** (US-032 + US-033, les « Should »). US-034 + US-035 (« Could »)
  → **Sprint 10**.

## Périmètre du sprint 9 (16 pts, cible v1.4.0)

| US | Titre | Pts | État |
|----|-------|-----|------|
| US-032 | Dashboards Analytics / Marketing / CRM / SaaS | 8 | 🔲 à faire (démarrer ici) |
| US-033 | Pages type : Settings, Pricing, Invoice, Chat, File manager, Inbox | 8 | 🔲 à faire |

Ordre : **US-032 → US-033**. Assemblage de composants déjà livrés (charts via
`tailsfadmin--apexcharts`, tables, KPI, Tabs, Modal, Dropzone, Badge, ProgressBar, Ribbon).

## Décisions clés

1. **Données statiques** (ADR-002) ; **sans CDN** (ADR-004/006) — charts via le contrôleur
   ApexCharts déjà vendoré, aucune nouvelle lib.
2. **Réutilisation (règle des 3)** : nouveau composant bundle seulement si motif répété ≥ 3×.
   Kanban déjà livré (US-040) → retiré d'US-033 ; Tabs déjà dispo (US-036).
3. **US-034/US-035 → Sprint 10** (Could) : auth/utilitaires étendus + scaffolding `make:tailsfadmin-page`.

## Comment reprendre (workflow)

1. `git checkout main && git pull`.
2. Une branche par travail (`feature/us-032-...`), **TDD** (test fonctionnel → template/contrôleur).
3. Gates avant push : bundle `composer test`/`phpstan`/`cs` ; démo `Project Test Suite`,
   `lint:twig`+`lint:yaml` ; Biome + E2E + axe-core en CI.
4. **PR → CI 6 jobs verte → merge → supprimer la branche.** Jamais de merge en rouge.

## Repères

- Décomposition : `tasks/README.md` + `tasks/US-032-tasks.md` + `tasks/US-033-tasks.md`.
- Board : `task-board.md`. Goal/cérémonies : `sprint-goal.md`.
- Convention pages démo : contrôleur `App\Controller\*` (`#[Route]`) + template étendant
  `@Tailsfadmin/layout/admin.html.twig` (blocs `title`/`breadcrumb`/`content`), menu dans
  `demo/config/packages/tailsfadmin.yaml` + i18n `demo/translations/messages.{fr,en,ar}.yaml`.
