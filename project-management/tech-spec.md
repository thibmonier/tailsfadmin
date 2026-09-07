# Spécification Technique — tailsfadmin

**Version :** 1.0.0 · **Date :** 2026-09-07 · **Statut :** Draft · **Track :** Standard
**Sources :** `prd.md`, `backlog/`, `architecture/`, `docs/adr/`

---

## 1. Objet

Spécifier l'implémentation du thème d'administration **tailsfadmin** : un **bundle Symfony réutilisable** portant le design system TailAdmin en **Symfony UX** (Twig Components + Stimulus + AssetMapper, Tailwind v4), et une **application de démo** qui le consomme. Ce document consolide les décisions ; le détail vit dans les ADR et les diagrammes C4.

Projet de type **thème/librairie UI** → **pas de modèle de données ni d'API métier** (les sections ERD/API classiques sont sans objet ; remplacées par l'**inventaire de composants** et la **chaîne d'assets**).

---

## 2. Stack & dépendances

| Domaine | Choix | Package(s) |
|---------|-------|-----------|
| Framework | Symfony 8.1 | `symfony/framework-bundle` |
| Runtime | FrankenPHP / PHP 8.5 | image `dunglas/frankenphp` |
| Assets | AssetMapper + importmap | `symfony/asset-mapper` |
| CSS | Tailwind v4 (sans Node) | `symfonycasts/tailwind-bundle` (**ADR-001**) |
| JS / interactivité | Stimulus + Turbo | `symfony/stimulus-bundle`, `symfony/ux-turbo` |
| Composants | Twig Components (+ Live si besoin) | `symfony/ux-twig-component`, `symfony/ux-live-component` |
| i18n | Translation + RTL | `symfony/translation` (**ADR-005**) |
| Libs JS (vendorées) | via `importmap:require` | apexcharts, fullcalendar, flatpickr, dropzone, jsvectormap, swiper |
| Tests | Pest 4 / PHPUnit 12 | `pestphp/pest`, `phpunit/phpunit` |
| Qualité | PHPStan max, PSR-12 | `phpstan/phpstan`, `friendsofphp/php-cs-fixer` |

---

## 3. Architecture

Vue d'ensemble : voir `architecture/c4-context.md`, `c4-container.md`, `c4-component.md`.

- **Bundle** (`TailsfadminBundle`, `AbstractBundle`) = le produit : composants Twig, contrôleurs Stimulus (`assets/dist`), layout, `MenuBuilder`, Twig Extension (`is_active`, icônes), tokens CSS.
- **App de démo** = usage : routes + templates assemblant les composants.
- **Chaîne d'assets** = AssetMapper + tailwind-bundle + stimulus-bundle + importmap.
- **Frontière stricte** bundle/démo (**ADR-002**, DoD §6).

---

## 4. Stratégie de composants

Détail exhaustif : `architecture/component-inventory.md` (**57 composants Twig**, dont **35 interactifs** ; **13 contrôleurs Stimulus**).

- **Nommage** : préfixe court `tsf` + sous-espaces `Ui / Layout / Form / Dashboard / Profile / Auth` (ex. `<twig:tsf:Ui:Alert>`).
- **Présentational** → Twig Components purs (échappement Twig par défaut).
- **Interactif** → Twig Component + `data-controller` vers un contrôleur Stimulus du bundle (**ADR-004**).
- **Compatibilité Symfony Forms** pour les composants de champ (form theme).

**Contrôleurs Stimulus (13)** : `theme`, `sidebar`, `dropdown`, `modal`, `alert-dismiss`, `datepicker` (flatpickr), `dropzone`, `multi-select`, `apexcharts`, `vectormap` (jsvectormap), `calendar` (fullcalendar), `preloader`, `video-player`. Chaque wrapper de lib est **dark-mode aware** (observation de la classe du `<html>`) et nettoie ses ressources sur `disconnect()`.

---

## 5. Chaîne d'assets (résumé)

1. **CSS** : entrée `assets/styles/app.css` (`@import "tailwindcss";`, `@plugin "@tailwindcss/forms";`, tokens en `@theme{}`) compilée par `tailwind:build` (binaire v4 standalone, **ADR-001**).
2. **JS** : contrôleurs Stimulus exposés via `package.json` (`symfony.controllers`) + mot-clé `symfony-ux` → auto-enregistrés par Flex (**ADR-003**). Libs vendorées via `importmap:require`.
3. **Dark mode** : stratégie classe sur `<html>` (`dark:` Tailwind), persistance `localStorage` (contrôleur `theme`).
4. **RTL** : variantes `ltr:`/`rtl:`, attribut `dir` selon locale (**ADR-005**).

⚠️ **POC Sprint 1 (US-002)** : prouver la chaîne binaire v4 → AssetMapper → rendu, et l'auto-enregistrement d'un contrôleur du bundle sur une app hôte.

---

## 6. Internationalisation & RTL

Voir **ADR-005**. `EventSubscriber` (session → cookie → défaut, whitelist), route `/locale/{locale}` (persistance + `dir`), sélecteur de langue (Twig dropdown). Catalogues `en`/`fr` complets ; `ar`(RTL)/`es`/`de` en structure. Tester la combinatoire **dark × RTL** sur sidebar/dropdowns/modals.

---

## 7. Stratégie de test

| Niveau | Cible | Outils |
|--------|-------|--------|
| Unitaire | `MenuBuilder`, Twig Extension (`is_active`), config | Pest/PHPUnit |
| Composant | Rendu des Twig Components (props, variantes, slots) | `ux-twig-component` test helpers |
| Fonctionnel | Routes/pages de démo (statut, présence des composants) | WebTestCase |
| Accessibilité | axe-core sur les pages de démo (violation = échec CI) | axe-core (US-025) |
| Qualité | PHPStan **niveau max**, PSR-12 | phpstan, php-cs-fixer |

Objectif : **couverture ≥ 80 %** du code PHP (NFR-05, DoD §5). Contrôleurs Stimulus : tests d'interaction ciblés (focus trap, clavier) sur les composants critiques (modal, dropdown, sidebar).

---

## 8. Déploiement / exécution

- **FrankenPHP / PHP 8.5** (worker mode) via Docker (**US-003**). `Dockerfile` + `compose.yaml` dans la démo.
- Prod : `APP_ENV=prod`, `asset-map:compile`, `tailwind:build --minify`, debug off, en-têtes de sécurité (`architecture/security.md`).
- Le **bundle** se distribue via Composer (SemVer, CHANGELOG, `export-ignore` de `demo/`) — **US-026**.

---

## 9. Couverture des exigences

| Exigence (PRD) | Décision / artefact |
|----------------|---------------------|
| FR-01/02/03 (bundle+démo, AssetMapper/Tailwind, layout) | ADR-001/002/003, C4, EPIC-001/002 |
| FR-04 dark mode | Contrôleur `theme`, `dark:` Tailwind (US-005) |
| FR-05 sidebar responsive | Contrôleur `sidebar` + `MenuBuilder` (US-006) |
| FR-07/08 UI kit + modals | Twig Components + `modal`/`dropdown` (EPIC-003) |
| FR-11/12 datepicker/upload | Wrappers `datepicker`/`dropzone` (US-015/016) |
| FR-13/14/15 charts/map/calendar | Wrappers `apexcharts`/`vectormap`/`calendar` (EPIC-005) |
| FR-19 i18n | ADR-005 (US-024) |
| NFR-01 fidélité | `component-inventory.md`, revue design par page |
| NFR-02 a11y | Contrôleurs accessibles + axe-core CI (US-025) |
| NFR-04/05 qualité/tests | PHPStan max, ≥80 % (US-026) |
| NFR-06/07 perf/idiomatique | AssetMapper vendoré, 100 % Stimulus (ADR-004) |
| NFR-10 sécurité | `architecture/security.md` |

---

## 10. Risques

| Risque | Sévérité | Mitigation |
|--------|----------|------------|
| Tailwind v4 ⇄ AssetMapper | Élevée | **POC Sprint 1** (US-002), ADR-001, version binaire épinglée |
| Auto-enregistrement contrôleurs bundle sur app tierce | Moyenne | Tester hors démo (ADR-003), US-026 |
| CSP stricte vs styles inline des libs (ApexCharts) | Moyenne | Tester la CSP réelle par page (security.md) |
| Portage a11y (focus trap, clavier) | Moyenne | Contrôleurs dédiés testés (modal/dropdown/sidebar) |
| Dérive de périmètre (UI kit large) | Moyenne | MoSCoW, `Could` en fin de backlog |

---

## 11. Références

- Décisions : `docs/adr/` (ADR-001 → ADR-005)
- Architecture : `architecture/c4-*.md`, `component-inventory.md`, `security.md`
- Produit : `prd.md`, `personas.md`, `backlog/`, `definition-of-done.md`
- Sprint 1 : `sprints/sprint-001-walking_skeleton/sprint-goal.md`
