# Tâches — Sprint 006 (Pages applicatives & i18n)

> Sprint d'**assemblage** : compose les pages TailAdmin avec la bibliothèque existante. Types dominants `[FE-WEB]` (pages/templates), `[BE]` (i18n : subscriber, route, catalogues), `[TEST]`. Pas de DB/API/Flutter.
> **Revue visuelle systématique** (leçon Sprint 5) : chaque page validée au navigateur/screenshot, pas seulement aux tests DOM.

## Vue d'ensemble

| US | Titre | Points | Tâches | Heures | Statut |
|----|-------|--------|--------|--------|--------|
| US-021 | Dashboard e-commerce | 5 | 6 | 12h | 🔲 |
| US-022 | Page profil + modals | 5 | 5 | 10h | 🔲 |
| US-023 | Pages auth + utilitaires | 5 | 6 | 11h | 🔲 |
| US-024 | i18n FR/EN + RTL + sélecteur | 8 | 8 | 18h | 🔲 |
| — | Tâches techniques (E2E page + revue visuelle) | — | 2 | 3h | 🔲 |

**Total : 27 tâches · ~54h · 23 pts** (marge sous capacité ~60h)

## Répartition par type

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [FE-WEB] | 15 | 33h | 61% |
| [BE] | 4 | 10h | 19% |
| [TEST] | 5 | 9h | 17% |
| [REV] | 3 | 2h | 4% |

## Ordre d'exécution recommandé

```
US-021 (dashboard — assemble charts/tables/cards/badges [+ carte US-019 si rapide])
→ US-022 (profil, réutilise modals) → US-023 (auth/utilitaires) → US-024 (i18n + RTL, transversal)
→ T-TECH (E2E parcours page + revue visuelle finale)
```

## Capitalisation (tout est déjà construit)

- **US-021** = assemblage pur : `tsf:Chart:Line/Bar` (S5), `tsf:Ui:Table/TableAdvanced` (S4), `tsf:Ui:Card/Badge/Avatar` (S3/S4), carte (US-019 optionnelle).
- **US-022** réutilise `tsf:Ui:Modal` (S3), `tsf:Ui:Avatar`, composants form (S4).
- **US-023** réutilise le layout (S1), form + form theme (S4), buttons (S3).
- **US-024** : classes `ltr:`/`rtl:` déjà posées (sidebar S2), sélecteur dans le header (S2). ADR-005.

## Décisions

- **i18n (ADR-005)** : composant `Translation` + `EventSubscriber` (session→cookie→défaut, whitelist), route `/locale/{locale}`, catalogues `en`/`fr` complets + `ar`(RTL)/`es`/`de` en structure.
- **US-019 carte** : intégrée dans US-021 **si** `importmap:require jsvectormap` + wrapper Stimulus est rapide (pattern connu) ; sinon placeholder + US-019 reste au backlog.

## Fichiers
- [US-021 — Dashboard](./US-021-tasks.md) · [US-022 — Profil](./US-022-tasks.md)
- [US-023 — Auth & utilitaires](./US-023-tasks.md) · [US-024 — i18n](./US-024-tasks.md)
- [Tâches techniques](./technical-tasks.md)

## Conventions
- **ID** : `T-<US>-<NN>` ; transverses `T-TECH-NN`. **Taille** 0.5–8h. **TDD** + **revue visuelle**.
- **Statuts** : 🔲 · 🔄 · 👀 · ✅ · 🚫.
