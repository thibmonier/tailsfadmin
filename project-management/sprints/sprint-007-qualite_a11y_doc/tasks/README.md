# Tâches — Sprint 007 (Qualité, accessibilité & livrabilité)

## Vue d'ensemble

| US | Titre | Points | Tâches | Heures | Statut |
|----|-------|--------|--------|--------|--------|
| US-025 | Accessibilité WCAG 2.2 AA (transversal) | 5 | 6 | 13.5h | 🔲 To Do |
| US-026 | CI, documentation & livrabilité | 8 | 8 | 17h | 🔲 To Do |

**Total : 14 tâches · 30.5h** (+ tâches transverses documentées)

## Répartition par type

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [OPS] | 3 | 7h | 23 % |
| [FE-WEB] | 2 | 6h | 20 % |
| [TEST] | 3 | 7h | 23 % |
| [DOC] | 4 | 9h | 30 % |
| [REV] | 2 | 1.5h | 5 % |

> Pas de [DB]/[BE]/[FE-MOB] : projet **bundle Symfony + démo** (pas de persistance, ni mobile). Sprint de finition/livrabilité.

## Ordre recommandé

`US-025 (a11y : axe-core → corrections → E2E) → US-026 (CI intègre axe + couverture + biome, puis doc)`

> US-025 **bloque** US-026 (T-026-02 intègre l'audit axe-core en CI).

## Fichiers
- [US-025 — Accessibilité](./US-025-tasks.md)
- [US-026 — CI & documentation](./US-026-tasks.md)
- [Tâches techniques transverses](./technical-tasks.md)

## Conventions
- **ID** : T-[US]-[Numéro] (ex : T-025-03)
- **Taille** : 0.5h – 8h max
- **Statuts** : 🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué
