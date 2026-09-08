# Tâches — Sprint 005 (Data-viz & widgets riches)

> Sprint « intégration de libs JS via Stimulus » (ADR-004). Types dominants `[FE-WEB]` (contrôleurs wrappers + Twig), `[OPS]` (vendoring importmap), `[TEST]`. Chaque lib : vendorée via `importmap:require`, encapsulée en contrôleur Stimulus (connect/disconnect, values/targets, dark-mode aware).

## Vue d'ensemble

| US | Titre | Points | Lib | Tâches | Heures | Statut |
|----|-------|--------|-----|--------|--------|--------|
| US-015 | Datepicker (flatpickr) | 3 | flatpickr | 4 | 6.5h | 🔲 |
| US-016 | Upload (Dropzone) | 5 | Dropzone | 5 | 9h | 🔲 |
| US-018 | Graphiques ApexCharts | 8 | ApexCharts | 6 | 15h | 🔲 |
| US-020 | Calendrier FullCalendar + modal | 8 | FullCalendar | 6 | 14h | 🔲 |
| — | Tâches techniques (Panther étendu + galerie) | — | — | 2 | 4h | 🔲 |

**Total : 23 tâches · ~48.5h · 24 pts** (marge sous capacité ~60h)

## Répartition par type

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [FE-WEB] | 13 | 30h | 62% |
| [OPS] | 4 | 8h | 16% |
| [TEST] | 4 | 8.5h | 18% |
| [REV] | 2 | 2h | 4% |

## Ordre d'exécution recommandé

```
US-015 (flatpickr — petit, VALIDE le pattern wrapper + vendoring CSS lib)
→ US-016 (Dropzone) → US-018 (ApexCharts, gros) → US-020 (FullCalendar + modal US-011)
→ T-TECH-01 (Panther étendu) → T-TECH-02 (galerie /ui-kit)
```

## Pattern wrapper commun (ADR-004) — appliqué à chaque lib

1. `php bin/console importmap:require <lib>` (vendoré, pas de CDN runtime).
2. Contrôleur Stimulus `<lib>_controller.js` : `connect()` monte la lib sur `this.element`/target, `disconnect()` détruit l'instance (pas de fuite), données via `values`, options via `data-*`.
3. **Dark-mode aware** : observer la classe `.dark` sur `<html>` (MutationObserver) → adapter le thème de la lib.
4. **CSS de la lib** (flatpickr, FullCalendar) : importé via AssetMapper / `@import` dans le CSS, pas de CDN.
5. Composant Twig `tsf:*` qui pose l'élément + `data-controller`.

## Capitalisation

- **US-020** réutilise le contrôleur `modal` (US-011) pour créer/éditer un événement.
- **US-015** s'intègre aux composants form (US-014).
- **US-018** alimente le futur dashboard e-commerce (US-021, Sprint 6).
- **Garde-fous** : smoke CSS (étendre aux CSS des libs) + Panther (étendre à 1-2 comportements JS).

## Fichiers
- [US-015 — Datepicker](./US-015-tasks.md) · [US-016 — Upload](./US-016-tasks.md)
- [US-018 — ApexCharts](./US-018-tasks.md) · [US-020 — FullCalendar](./US-020-tasks.md)
- [Tâches techniques](./technical-tasks.md)

## Conventions
- **ID** : `T-<US>-<NN>` ; transverses `T-TECH-NN`. **Taille** 0.5–8h. **TDD** + vérif runtime (Panther) pour le JS.
- **Statuts** : 🔲 · 🔄 · 👀 · ✅ · 🚫.
