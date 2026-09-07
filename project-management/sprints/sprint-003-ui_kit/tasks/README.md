# Tâches — Sprint 003 (UI Kit)

> Projet thème/bundle → types dominants `[FE-WEB]` (Twig Components + Stimulus), `[TEST]`, `[REV]`. Pas de DB/API/Flutter.
> Convention `tsf` (sous-espace `Ui`). CSS composant complet (fix Sprint 2). Contrôleur `dropdown` déjà en place (US-007).

## Vue d'ensemble

| US | Titre | Points | Tâches | Heures | Statut |
|----|-------|--------|--------|--------|--------|
| US-008 | Alerts (4 variantes) | 3 | 5 | 7.5h | 🔲 |
| US-009 | Badges & Avatars | 3 | 4 | 6.5h | 🔲 |
| US-010 | Buttons (6 variantes) | 3 | 4 | 6.5h | 🔲 |
| US-011 | Modals / overlays (Stimulus) | 5 | 5 | 11h | 🔲 |
| US-012 | Dropdowns (Twig sur contrôleur existant) | 5 | 4 | 6.5h | 🔲 |
| — | Tâches techniques (garde-fous CSS + gallery) | — | 3 | 6.5h | 🔲 |

**Total : 25 tâches · ~44.5h · 19 points** (marge sous capacité ~60h)

## Répartition par type

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [FE-WEB] | 13 | 27h | 61% |
| [TEST] | 6 | 12.5h | 28% |
| [OPS] | 1 | 3h | 7% |
| [REV] | 5 | 3.5h | (arr.) |

## Ordre de développement recommandé

```
Garde-fous (T-TECH-01 smoke CSS) → US-008 → US-009 → US-010 → US-012 → US-011 (modal, focus trap)
→ T-TECH-02 (spike Panther sur modal/dropdown) → T-TECH-03 (page galerie /ui-kit)
```

## Garde-fous issus de l'incident CSS (Sprint 2)

- **T-TECH-01** : smoke-test du CSS compilé (présence `.menu-item`/`flex`) → attrape une régression de style.
- **T-TECH-02** : 1er test navigateur `symfony/panther` (comportement JS réel : modale/dropdown).

## Fichiers
- [US-008 — Alerts](./US-008-tasks.md) · [US-009 — Badges & Avatars](./US-009-tasks.md) · [US-010 — Buttons](./US-010-tasks.md)
- [US-011 — Modals](./US-011-tasks.md) · [US-012 — Dropdowns](./US-012-tasks.md)
- [Tâches techniques](./technical-tasks.md)

## Conventions
- **ID** : `T-<US>-<NN>` ; transverses `T-TECH-NN`. **Taille** 0.5–8h. **TDD** (RED→GREEN→REFACTOR).
- **Statuts** : 🔲 · 🔄 · 👀 · ✅ · 🚫.
