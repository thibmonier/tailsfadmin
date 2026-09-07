# Tâches — Sprint 002 (Navigation & layout interactifs)

> Sprint Planning Part 2. Projet **thème/bundle** → types dominants `[FE-WEB]`, `[TEST]`, `[BE]` (services PHP du bundle), `[DOC]`, `[REV]`, `[OPS]`. Pas de DB/API Platform/Flutter.
> Le « comment » suit les ADR (préfixe `tsf`, contrôleurs Stimulus distribués depuis le bundle — mécanisme prouvé au Sprint 1).

## Vue d'ensemble

| US | Titre | Points | Tâches | Heures | Statut |
|----|-------|--------|--------|--------|--------|
| US-005 | Dark mode persistant (Stimulus) | 5 | 5 | 10h | 🔲 |
| US-006 | Sidebar responsive multi-niveaux | 8 | 7 | 20h | 🔲 |
| US-007 | Header (recherche Cmd+K, dropdowns) | 8 | 6 | 18h | 🔲 |
| — | Tâches techniques transverses (actions rétro) | — | 3 | 2.5h | 🔲 |

**Total : 21 tâches · ~50.5h · 21 points** (marge sous la capacité ~60h)

## Répartition par type

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [FE-WEB] | 10 | 29h | 57% |
| [BE] | 2 | 6h | 12% |
| [TEST] | 3 | 9h | 18% |
| [DOC] | 2 | 1.5h | 3% |
| [OPS] | 2 | 2h | 4% |
| [REV] | 3 | 3h | 6% |

## Ordre d'exécution recommandé

```
T-TECH-01/02 (actions rétro) → US-005 (dark mode) → US-006 (sidebar) → US-007 (header)
```
US-005 débloque le bouton toggle déjà réservé (US-004). US-006 introduit le `MenuBuilder`. US-007 crée le contrôleur `dropdown` générique (réutilisé par US-006 si besoin).

## Capitalisation Sprint 1

- Contrôleur Stimulus **déjà distribué** depuis le bundle (`preloader`) → les nouveaux contrôleurs (`theme`, `sidebar`, `dropdown`, `search`) s'ajoutent au `package.json` `symfony.controllers` existant.
- Tokens **dark** déjà définis (US-002) → US-005 n'a plus qu'à piloter la classe `.dark`.
- Sidebar **statique** en place (US-004) → US-006 la rend interactive.

## Fichiers
- [US-005 — Dark mode persistant](./US-005-tasks.md)
- [US-006 — Sidebar responsive multi-niveaux](./US-006-tasks.md)
- [US-007 — Header](./US-007-tasks.md)
- [Tâches techniques transverses](./technical-tasks.md)

## Conventions
- **ID** : `T-<US>-<NN>` ; transverses : `T-TECH-NN`.
- **Taille** : 0.5h – 8h. **TDD** : test d'abord (RED→GREEN→REFACTOR).
- **Statuts** : 🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué.
