# Tâches — Sprint 008 (Distribution & consommabilité)

## Vue d'ensemble

| US | Titre | Points | Tâches | Heures | Statut |
|----|-------|--------|--------|--------|--------|
| US-027 | Recette d'assets — importmap fourni par le bundle | 8 | 8 | 18h | 🔲 To Do |
| US-028 | Thème CSS Tailwind distribuable + personnalisable | 5 | 6 | 10.5h | 🔲 To Do |
| US-030 | Test d'intégration app Symfony vierge (CI) | 5 | 6 | 11.5h | 🔲 To Do |
| US-031 | Publication Packagist | 3 | 6 | 7h | 🔲 To Do |
| US-029 | Flex recipe (config auto) | 5 | 6 | 11h | 🟣 Réserve/stretch |

**Engagé : 21 points · 26 tâches · 47h** (+ US-029 stretch : 5 pts · 6 tâches · 11h)

## Répartition par type (engagé)

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [BE] | 4 | 12h | 26 % |
| [FE-WEB] | 4 | 9h | 19 % |
| [OPS] | 6 | 11h | 23 % |
| [TEST] | 4 | 9h | 19 % |
| [DOC] | 4 | 4.5h | 10 % |
| [REV] | 4 | 2h | 4 % |

> Pas de [DB]/[FE-MOB] : projet **bundle Symfony + démo** (pas de persistance ni mobile). Sprint **enabler de distribution** — le template CRUD/Flutter de la commande est adapté à la nature packaging du travail.

## Ordre recommandé

`US-027 (assets/importmap) + US-028 (CSS) → US-030 (test app vierge, valide 027/028) → US-031 (Packagist, après intégration prouvée) → US-029 (recette, si stretch)`

> **Packagist d'abord** mais publication (US-031) **après** US-030 verte, pour éviter une première version publique cassée.

## Fichiers
- [US-027 — importmap fourni par le bundle](./US-027-tasks.md)
- [US-028 — thème CSS distribuable](./US-028-tasks.md)
- [US-030 — test intégration app vierge (CI)](./US-030-tasks.md)
- [US-031 — publication Packagist](./US-031-tasks.md)
- [US-029 — Flex recipe (stretch)](./US-029-tasks.md)
- [Tâches techniques transverses](./technical-tasks.md)

## Conventions
- **ID** : T-[US]-[Numéro] (ex : T-027-03)
- **Taille** : 0.5h – 8h max
- **Statuts** : 🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué
