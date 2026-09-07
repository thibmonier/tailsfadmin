# Tâches — Sprint 001 (Walking Skeleton)

> Sprint Planning Part 2 — « Le Comment ». Projet **thème/bundle** : pas de base de données, d'API Platform ni de Flutter → types dominants `[OPS]`, `[FE-WEB]`, `[TEST]`, `[DOC]`, `[REV]`.
> Le « comment » suit les **ADR** (docs/adr/) : bundle en `src/` + app en `demo/` (ADR-002), Tailwind v4 via `symfonycasts/tailwind-bundle` (ADR-001), contrôleurs Stimulus packagés (ADR-003), préfixe composant `tsf`.

## Vue d'ensemble

| US | Titre | Points | Tâches | Heures | Statut |
|----|-------|--------|--------|--------|--------|
| US-001 | Squelette bundle + démo | 5 | 7 | 13h | 🔲 |
| US-002 | AssetMapper + Tailwind v4 + tokens | 8 | 8 | 18h | 🔲 |
| US-003 | Runtime FrankenPHP / PHP 8.5 | 3 | 5 | 8.5h | 🔲 |
| US-004 | Layout admin de base | 5 | 7 | 15h | 🔲 |
| — | Tâches techniques transverses | — | 4 | 9h | 🔲 |

**Total : 31 tâches · ~63.5h · 21 points**

> **US-005 (dark mode) déplacée en Sprint 2** (ajustement de charge). Sa décomposition reste dans `US-005-tasks.md`. La tâche de packaging des contrôleurs a été remontée en `T-TECH-04` (nécessaire au preloader d'US-004).

## Répartition par type

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [OPS] | 10 | 23h | 36% |
| [FE-WEB] | 10 | 21h | 33% |
| [TEST] | 4 | 10h | 16% |
| [DOC] | 3 | 3h | 5% |
| [BE] | 1 | 3h | 5% |
| [REV] | 4 | 4.5h | 7% |

## Capacité & alerte de charge

> **~63.5h pour un sprint de 2 semaines à 1 développeur** (~60h utiles) : charge désormais **alignée sur la capacité** après le déplacement d'US-005. Marge résiduelle faible → surveiller le **POC Tailwind v4 (US-002)**, seul point d'incertitude. Si le POC déborde, US-003 (Docker, indépendant) peut glisser légèrement.

## Fichiers
- [US-001 — Squelette bundle + démo](./US-001-tasks.md)
- [US-002 — AssetMapper + Tailwind v4 + tokens](./US-002-tasks.md)
- [US-003 — Runtime FrankenPHP](./US-003-tasks.md)
- [US-004 — Layout admin de base](./US-004-tasks.md)
- [US-005 — Dark mode persistant](./US-005-tasks.md) — *déplacée en Sprint 2*
- [Tâches techniques transverses](./technical-tasks.md)

## Conventions
- **ID** : `T-<US>-<NN>` (ex. `T-001-05`) ; transverses : `T-TECH-NN`.
- **Taille** : 0.5h – 8h (idéal 2-4h).
- **Statuts** : 🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué.
- **TDD** : pour chaque tâche testable, écrire le test d'abord (RED → GREEN → REFACTOR).

## Ordre d'exécution recommandé (chemin critique)

```
T-TECH-01/02 (outillage) → US-001 → T-TECH-04 (packaging) → US-002 (POC) → US-004
```
US-003 (Docker) peut avancer **en parallèle** de US-002 (indépendant du CSS). US-005 est reportée au Sprint 2.
