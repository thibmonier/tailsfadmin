# Tâches — Sprint 004 (Cards, formulaires & tables)

> Projet thème/bundle → types dominants `[FE-WEB]` (Twig Components + form theme), `[BE]` (form theme/extension), `[TEST]`, `[REV]`. Pas de DB/API/Flutter.
> Convention `tsf` (sous-espaces `Ui`, `Form`). Garde-fous en place (smoke CSS, Panther).

## Vue d'ensemble

| US | Titre | Points | Tâches | Heures | Statut |
|----|-------|--------|--------|--------|--------|
| US-013 | Cards, media, grid images, videos | 5 | 5 | 9h | 🔲 |
| US-014 | Formulaires (composants `tsf:Form:*` + form theme) | 8 | 8 | 20h | 🔲 |
| US-017 | Tables (basiques + avancées) | 5 | 5 | 10h | 🔲 |
| — | Tâches techniques (galerie + smoke étendu) | — | 2 | 3h | 🔲 |

**Total : 20 tâches · ~42h · 18 pts** (marge sous capacité ~60h)

## Répartition par type

| Type | Tâches | Heures | % |
|------|--------|--------|---|
| [FE-WEB] | 12 | 26h | 62% |
| [BE] | 2 | 5h | 12% |
| [TEST] | 4 | 8h | 19% |
| [REV] | 2 | 3h | 7% |

## Ordre d'exécution recommandé

```
US-013 (cards, présentational, rapide) → US-014 (composants Form autonomes → form theme) → US-017 (tables, réutilise dropdown)
→ T-TECH-01 (galerie enrichie) → T-TECH-02 (smoke CSS étendu form)
```

## Décision d'architecture US-014 (actée)

**Les deux** :
1. **Composants autonomes** `tsf:Form:{Input,Select,Checkbox,Radio,Toggle,Textarea,InputGroup}` — usage direct dans un template, fidèles TailAdmin.
2. **Form theme Symfony** (`@Tailsfadmin/form/theme.html.twig`) qui rend `{{ form_row(form.x) }}` en style TailAdmin, en réutilisant les mêmes classes. Priorité aux composants ; le theme peut déborder en Sprint 5.

## Capitalisation

- **US-017** réutilise le contrôleur `dropdown` (US-012) pour les actions par ligne, et `tsf:Ui:Badge`/`Avatar` dans les cellules.
- CSS de formulaire déjà porté (`@tailwindcss/forms`, `.form-check-input`, `.tableCheckbox`).

## Fichiers
- [US-013 — Cards & médias](./US-013-tasks.md)
- [US-014 — Formulaires](./US-014-tasks.md)
- [US-017 — Tables](./US-017-tasks.md)
- [Tâches techniques](./technical-tasks.md)

## Conventions
- **ID** : `T-<US>-<NN>` ; transverses `T-TECH-NN`. **Taille** 0.5–8h. **TDD** (RED→GREEN→REFACTOR).
- **Statuts** : 🔲 · 🔄 · 👀 · ✅ · 🚫.
