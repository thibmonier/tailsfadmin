# Tâches — Sprint 011 (Composants & pages, EPIC-010)

## Vue d'ensemble

| US | Titre | Points | Tâches | Heures | Statut |
|----|-------|--------|--------|--------|--------|
| US-036 | Composants : Tabs, Progress bars, Ribbons | 8 | 12 | 23.5h | ✅ Done |
| US-037 | 6 layouts d'exemple (variantes de shell) | 8 | 7 | 18.5h | 🔲 To Do |
| US-038 | Page Form Layout | 3 | 4 | 7h | 🔲 To Do |
| US-039 | Page Integrations / API keys | 5 | 6 | 13h | 🔲 To Do |
| US-040 | Task list : liste + Kanban | 8 | 6 | 16h | 🔲 To Do |
| — | Tâches techniques transverses | — | 2 | 1.5h | 🔲 To Do |

**Total : 37 tâches · 79.5h · 32 points**

## Répartition par type

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [BE] | 3 | 6h | 8 % |
| [FE-WEB] | 21 | 49.5h | 62 % |
| [TEST] | 6 | 15h | 19 % |
| [DOC] | 1 | 1h | 1 % |
| [REV] | 5 | 7.5h | 9 % |
| [OPS] | 1 | 0.5h | 1 % |
| [DB]/[FE-MOB]/[API] | 0 | — | — |

> Sprint **UI/composants** : dominante `[FE-WEB]` (composants Twig + Stimulus + pages démo).
> Pas de `[DB]`/`[API]`/`[FE-MOB]` — nature bundle Symfony + démo.

## Ordre recommandé

`US-036 (enabler composants) → US-037 / US-038 / US-039 / US-040 (parallélisables)`

## Fichiers
- [US-036 — Composants Tabs/Progress/Ribbons](./US-036-tasks.md)
- [US-037 — 6 layouts d'exemple](./US-037-tasks.md)
- [US-038 — Page Form Layout](./US-038-tasks.md)
- [US-039 — Page Integrations / API keys](./US-039-tasks.md)
- [US-040 — Task list + Kanban](./US-040-tasks.md)
- [Tâches techniques transverses](./technical-tasks.md)

## Conventions
- **ID** : T-[US]-[NN] (ex. T-036-07)
- **Taille** : 0.5h – 8h (idéal 2-4h)
- **Statuts** : 🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué
- **Sans CDN** ; nouveaux contrôleurs déclarés dans les deux `package.json` (garde US-019).
