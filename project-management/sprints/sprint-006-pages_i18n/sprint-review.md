# Sprint Review — Sprint 006 (Pages applicatives & internationalisation)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-08 |
| Sprint | 006 — Pages & i18n (sprint d'assemblage) |
| Contexte | Projet solo — inspection de l'incrément |

## Sprint Goal

> **Donner vie au thème comme un vrai back-office : assembler un dashboard e-commerce complet à partir des composants existants, livrer les pages profil et authentification, et internationaliser l'interface (FR/EN + structure RTL).**

**Atteint : ✅ OUI** — 4 pages phares assemblées à partir de la bibliothèque, i18n FR/EN opérationnelle avec structure RTL (arabe) et sélecteur de langue.

## User Stories livrées

| ID | Titre | Points | Statut |
|----|-------|--------|--------|
| US-021 | Dashboard e-commerce (métriques, cible, ventes, commandes, démographie) | 5 | ✅ Livré |
| US-022 | Page profil + modales d'édition (infos, adresse) | 5 | ✅ Livré |
| US-023 | Pages auth + utilitaires (signin, signup, blank, 404) | 5 | ✅ Livré |
| US-024 | i18n FR/EN + structure RTL + sélecteur de langue | 8 | ✅ Livré |

**Engagé : 23/23 points (100 %)**

### Extras livrés dans la foulée
| Élément | Nature |
|---------|--------|
| **US-019** — Carte vectorielle jsvectormap (démographie) | 5 pts (Could, sortie du backlog) |
| `fix(modal)` focus trap KO | Correctif a11y (bug US-011 révélé par la revue console) |
| `feat(button)` prop `block` | Enhancement composant (US-010) |
| `fix(dark-mode)` inversion des gris | Dette systémique résolue (décision A, approche TailAdmin) |

## Métriques

| Métrique | Valeur |
|----------|--------|
| Points engagés / livrés | 23 / 23 (+ US-019 = 28 avec l'extra) |
| Vélocité | 23 (S1 21, S2 21, S3 19, S4 18, S5 24, **S6 23**) |
| Tests | **180 fonctionnels + 6 E2E Panther** |
| PHPStan (max) | 0 erreur |
| php-cs-fixer | 0 |
| Régression | 0 |

## Démonstration (reproductible)

```bash
cd demo && composer test          # 180/180 (rapide, sans navigateur)
composer test:e2e                 # 6/6 Panther (montage JS RÉEL, dont carte + focus trap)
symfony server:start -d --no-tls  # puis parcourir :
#   /              → dashboard e-commerce (KPI, jauge radiale, charts, table, carte monde)
#   /profile       → carte profil + 2 modales d'édition
#   /auth/login    /auth/register  /pages/blank  → pages auth & gabarit
#   /locale/en /locale/ar → bascule de langue (RTL pour ar)
```
- **Revue visuelle** capturée clair **et** dark pour chaque page, **dont dark×RTL** (sidebar à droite, layout miroir en arabe) — action reportée des rétros précédentes enfin réalisée.
- **Montage JS prouvé** (E2E) : SVG de la carte jsvectormap (176 régions), focus trap piégé dans le dialog.

## Incrément produit

- **Dashboard e-commerce** (`/`) : tuiles KPI + badges de variation, jauge `radialBar`, `tsf:Chart:Bar`/`Line`, table commandes (Avatar + Badge), **carte du monde jsvectormap** (marqueurs France/USA, dark-aware).
- **Page profil** (`/profile`) : carte méta (Avatar, identité, réseaux), blocs infos/adresse, **2 modales** (`tsf:Ui:Modal` + `tsf:Form:*`), POST→PRG.
- **Pages auth & utilitaires** : layout auth centré du bundle, signin/signup (form + social + `tsf:Ui:Button block`), page vierge, **404 sans stack en prod** (override TwigBundle).
- **i18n** : `LocaleSubscriber` (bundle, session→whitelist), `LocaleController` (démo, redirect same-origin), catalogues chrome (bundle) + menu (démo) FR/EN/AR, `tsf_dir()`/`tsf_locales()`, `<html lang dir>` dynamique, classes `rtl:`, sélecteur de langue (réutilise `tsf:Ui:Dropdown`).
- **Nouveau composant** : `tsf:Chart:VectorMap` + contrôleur `tailsfadmin--vectormap` (ADR-004).
- **Dette dark-mode résolue** : inversion des gris retirée du `.dark` (approche TailAdmin) → inputs, dropdowns, modals, boutons secondary rendent correctement en sombre.

## Feedback / décisions

### Positif
- **Sprint d'assemblage réussi** : peu de nouveaux composants, la bibliothèque des sprints 1-5 s'est composée efficacement en pages fidèles à TailAdmin.
- **Revue console à haute valeur** : a débusqué un bug a11y (focus trap) invisible aux tests DOM/E2E.
- **Décision dark-mode fondée sur la source** : lecture du CSS TailAdmin → correctif net sans tâtonner ; dette systémique résolue.
- **RTL livré** : bascule complète du layout, chrome traduit, dark×RTL vérifié.

### À améliorer / suivi
- **ADR « intégration lib JS »** toujours à écrire (report S5) — matière enrichie (jsvectormap + manifeste `assets/package.json`). → US-026.
- **Garde visuelle dark** ciblée (anti-régression de la dette) → US-025/026.
- **Traduction du contenu de page** (le dashboard reste FR sous locale AR) : hors périmètre chrome, à décider si nécessaire.

### Impact sur le backlog
- **US-019 sortie du backlog** (livrée). Reste : **US-025 (accessibilité WCAG)** et **US-026 (CI & documentation)** → **Sprint 7** (dernier sprint, finition).

## Prochaines étapes
1. ✅ Rétrospective Sprint 6 (`sprint-retro.md`).
2. Pousser la branche sur origin.
3. Sprint 7 (US-025 accessibilité + US-026 CI/documentation).
