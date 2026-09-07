# Sprint Review — Sprint 004 (Cards, formulaires & tables)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-07 |
| Sprint | 004 — Contenu riche |
| Contexte | Projet solo — review = inspection de l'incrément |

## Sprint Goal

> **Permettre de composer des pages admin riches : cartes & médias, formulaires complets (compatibles Symfony Forms) et tables de données — en Twig Components fidèles à TailAdmin.**

**Atteint : ✅ OUI** — cards, 7 composants de formulaire + form theme Symfony, et tables (basique + avancée) livrés.

## User Stories livrées

| ID | Titre | Points | Statut |
|----|-------|--------|--------|
| US-013 | Cards, media cards, grid images, videos | 5 | ✅ Livré |
| US-014 | Formulaires (composants `tsf:Form:*` + form theme) | 8 | ✅ Livré |
| US-017 | Tables (basiques + avancées) | 5 | ✅ Livré |

**Livré : 18/18 points (100 %)**

## Métriques

| Métrique | Valeur |
|----------|--------|
| Points engagés / livrés | 18 / 18 |
| Vélocité | 18 (S1 21, S2 21, S3 19, S4 18) |
| Tâches | 20/20 |
| Tests | **117 fonctionnels + 2 E2E Panther** |
| PHPStan (max) | 0 erreur |
| php-cs-fixer | 0 |
| Régression | 0 |

## Démonstration (reproductible)

```bash
cd demo && composer test          # 117/117 (smoke CSS étendu form/table inclus)
composer test:e2e                 # 2/2 Panther
php -S 127.0.0.1:8055 -t public   # /ui-kit — galerie complète (10 sections, ancres)
```
- **Cards** : Card (slots), MediaCard (ratios), GridImage (validation `alt`), Video (HTTPS + `title` iframe).
- **Formulaires** : composants `tsf:Form:*` (Input, InputGroup, Select, Textarea, Checkbox, Radio, Toggle) **et** un vrai `DemoContactType` rendu via le **form theme** (`form_row` en style TailAdmin, état error/422 géré).
- **Tables** : basique (responsive `overflow-x-auto`) et avancée (sélection `.tableCheckbox`, tri, dropdown d'actions réutilisé, Badge/Avatar en cellule).

## Incrément produit

- **Composants** `tsf:Ui:` (Card, MediaCard, GridImage, Video, Table, TableAdvanced) et `tsf:Form:` (7).
- **Form theme** `@Tailsfadmin/form/theme.html.twig` + activation démo + `DemoContactType`.
- **CSS** : `.tableCheckbox`, `.form-check-input` portés en `@layer components`.
- **Galerie `/ui-kit`** enrichie (10 sections + sommaire à ancres).
- **Garde-fous** : smoke-test CSS étendu (form/table), E2E Panther maintenu.

## Feedback / décisions

### Positif
- **Décision « les deux » (US-014) réalisée** : composants autonomes + form theme Symfony → couvre l'usage direct (P-001) et Symfony Forms (validation/CSRF).
- **Réutilisation forte** : TableAdvanced réutilise le contrôleur `dropdown` (US-012) et les composants Badge/Avatar (US-009) — zéro nouveau JS.
- **Garde-fous efficaces** : smoke CSS étendu attrape une régression de port sur form/table.
- Bonne gestion des spécificités Symfony récentes (HTTP **422** sur form invalide).

### À améliorer / points de suivi
- **Extension navigateur déconnectée** en fin de sprint → rendu final vérifié par **curl** (sections + classes) faute de screenshot. À refaire en captures quand l'extension est reconnectée.
- Documenter le catalogue (props/slots) — la galerie est le support ; rattacher à US-026.

### Impact sur le backlog
- Aucun ajout. **Sprint 5** : EPIC-005 data-viz (US-018 ApexCharts, US-019 map, US-020 calendrier) + US-015/016 (datepicker/upload, reportés).

## Prochaines étapes
1. Rétrospective.
2. Commit du Sprint 4.
3. Sprint 5 (data-viz + datepicker/upload).
