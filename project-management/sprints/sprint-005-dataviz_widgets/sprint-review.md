# Sprint Review — Sprint 005 (Data-viz & widgets riches)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-08 |
| Sprint | 005 — Intégrations JS |
| Contexte | Projet solo — inspection de l'incrément |

## Sprint Goal

> **Intégrer les librairies JS riches de TailAdmin (graphiques, carte, calendrier, datepicker, upload) en contrôleurs Stimulus réutilisables, vendorés via importmap (sans CDN runtime) et dark-mode aware.**

**Atteint : ✅ OUI** — 4 libs (flatpickr, Dropzone, ApexCharts, FullCalendar) intégrées selon le pattern wrapper ADR-004, montage JS prouvé au navigateur.

## User Stories livrées

| ID | Titre | Points | Lib | Statut |
|----|-------|--------|-----|--------|
| US-015 | Datepicker | 3 | flatpickr 4.6.13 | ✅ Livré |
| US-016 | Upload | 5 | Dropzone 6.2.0 | ✅ Livré |
| US-018 | Graphiques | 8 | ApexCharts 7.1.0 | ✅ Livré |
| US-020 | Calendrier + modal | 8 | FullCalendar 6.1.21 | ✅ Livré |

**Livré : 24/24 points (100 %)**

> US-019 (carte jsvectormap, Could) non nécessaire — reste au backlog.

## Métriques

| Métrique | Valeur |
|----------|--------|
| Points engagés / livrés | 24 / 24 |
| Vélocité | 24 (S1 21, S2 21, S3 19, S4 18, S5 24) |
| Tâches | 23/23 |
| Tests | **157 fonctionnels + 5 E2E Panther** |
| PHPStan (max) | 0 erreur |
| php-cs-fixer | 0 |
| Régression | 0 |

## Démonstration (reproductible)

```bash
cd demo && composer test          # 157/157 (rapide, sans navigateur)
composer test:e2e                 # 5/5 Panther (montage JS RÉEL)
php -S 127.0.0.1:8055 -t public   # /ui-kit — 14 sections
```
- **Datepicker** (flatpickr), **Upload** (Dropzone, endpoint stub), **Graphiques** (ApexCharts line/bar/dashboard), **Calendrier** (FullCalendar + modale d'événement).
- **Montage JS prouvé** : E2E vérifient le SVG ApexCharts injecté, l'ouverture de flatpickr, la grille FullCalendar rendue.

## Incrément produit

- **Contrôleurs Stimulus wrappers** : `datepicker`, `dropzone`, `apexcharts`, `calendar` — tous `connect`/`disconnect` (destroy, zéro fuite), `values`/`targets`, **dark-mode aware** (MutationObserver).
- **Composants** : `tsf:Form:Datepicker`, `tsf:Form:Upload`, `tsf:Chart:Line`, `tsf:Chart:Bar`, `tsf:Calendar` (modale réutilisant `tsf:Ui:Modal` d'US-011, sans le modifier).
- **Vendoring** : 4 libs + deps (preact pour FC) via `importmap:require`, **zéro CDN runtime** ; CSS des libs servis localement (200) ou injectés par la lib.
- **Garde-fou runtime** : 3 nouveaux E2E Panther (montage réel), isolés de `composer test`.
- **Galerie `/ui-kit`** : 14 sections avec sommaire à ancres.

## Feedback / décisions

### Positif
- **Pattern wrapper (ADR-004) validé sur 4 vraies libs** : la recette de vendoring (`importmap:require <lib>/dist/lib.css` type:css) découverte en US-015 a servi telle quelle pour les suivantes → vélocité en hausse (24 pts).
- **Réutilisation** : le calendrier réutilise le contrôleur `modal` (US-011) via l'API Stimulus, sans le modifier.
- **Garde-fou runtime accompli** : on prouve désormais le montage JS réel (le trou identifié aux Sprints 2/3 est comblé pour de bon).
- Bonne gestion des pièges de résolution (stub v7 `@fullcalendar/core` → import v6.1.21).

### À améliorer / suivi
- **Extension Chrome toujours déconnectée** → rendu final vérifié par **curl** (14 sections + widgets câblés) et surtout par **Panther** (montage réel). Screenshots à reprendre quand l'extension revient.
- Documenter le catalogue (props/slots) — US-026.

### Impact sur le backlog
- Aucun ajout. **Sprint 6** : EPIC-006 pages applicatives (US-021 dashboard e-commerce — assemble les charts !, US-022 profil, US-023 auth, US-024 i18n) — le dashboard assemble enfin tous les composants.

## Prochaines étapes
1. Rétrospective.
2. Commit du Sprint 5.
3. Sprint 6 (pages applicatives & i18n).
