# Sprint 007 — Qualité, accessibilité & livrabilité

## Informations

| Attribut | Valeur |
|----------|--------|
| Numéro | 007 (dernier sprint — EPIC-007) |
| Durée | 2 semaines (10 j) |
| Capacité | ~19-24 pts (1 dev) |
| Engagé | **13 points** |
| Prérequis | Sprints 1-6 ✅ (bundle complet : fondations, nav, UI kit, formulaires/tables, data-viz, pages applicatives & i18n) |

## Sprint Goal

> **Transformer le thème en un bundle professionnel adoptable : garantir l'accessibilité WCAG 2.2 AA de toutes les pages (clavier, lecteur d'écran, contrastes clair/dark/RTL), et livrer la chaîne de qualité et de documentation (CI complète, README d'installation, catalogue de composants, CHANGELOG SemVer).**

C'est le sprint de **finition et de livrabilité** : aucune nouvelle fonctionnalité UI, on durcit la qualité et on rend le bundle installable par un tiers.

## Sprint Backlog

| Priorité | ID | Titre | Points | Statut |
|----------|-----|-------|--------|--------|
| 🔴 Must | US-025 | Audit & mise en conformité accessibilité (WCAG AA transversal) | 5 | 🔵 To Do |
| 🔴 Must | US-026 | CI, documentation & livrabilité du bundle | 8 | 🔵 To Do |

**Total engagé : 13 points**

## Ordre de développement recommandé

`US-025 (a11y : audit axe-core + corrections ARIA/focus/contraste, clair/dark/RTL) → US-026 (CI intègre les tests a11y + PHPStan/tests/lint + doc + CHANGELOG)`

> US-025 **bloque** US-026 (la CI doit intégrer les tests d'accessibilité).

## Capitalisation — tout est en place pour auditer

- **US-025** audite les pages déjà livrées (dashboard US-021, profil US-022, auth US-023) + le mode RTL (US-024). Les composants portent déjà de nombreux attributs ARIA (modals `role="dialog"`/`aria-modal`, dropdowns `role="menu"`/`aria-expanded`, sidebar `aria-expanded`, skip-link) ; l'audit valide et complète.
- **US-026** s'appuie sur l'outillage existant (PHPStan max 0, php-cs-fixer 0, PHPUnit 12, E2E Panther) et la CI GitHub Actions déjà amorcée (Sprint 1, T-TECH-04).

## Definition of Ready (vérifiée)

- [x] US-025 / US-026 : description, Gherkin, estimation, dépendances.
- [x] Pages de démo assemblées et fonctionnelles (Sprints 1-6).
- [x] Outillage qualité en place (PHPStan, cs-fixer, PHPUnit, Panther).
- [x] Dépôt poussé sur origin (branche `feature/sprint-001-walking-skeleton`).

## Dépendances

| US | Dépend de | Statut |
|----|-----------|--------|
| US-025 | US-021/022/023 (pages assemblées), US-024 (RTL à auditer) | ✅ |
| US-026 | US-001 (squelette bundle), US-025 (tests a11y en CI) | ✅ / US-025 en amont |

## Risques

| Risque | Prob. | Impact | Mitigation |
|--------|-------|--------|------------|
| Intégration axe-core en PHP/Panther chronophage (pas de pont natif) | Moyenne | Moyen | axe-core injecté via `executeScript` dans un test Panther (CDN évité : vendorer axe.min.js) ; sinon audit manuel documenté + garde ciblée |
| Violations de contraste en dark ET RTL (double combinatoire) | Moyenne | Moyen | Auditer les 2 thèmes × dir ; réutiliser la revue visuelle systématique (rodée S6) |
| Couverture < 80 % sur le bundle (démo peu couverte par le tooling racine) | Moyenne | Moyen | Cibler la couverture sur `src/` du bundle ; clarifier périmètre (bundle vs démo) |
| Seuil de couverture nécessitant PCOV/Xdebug en CI | Faible | Faible | Configurer PCOV dans le job CI |
| Garde visuelle dark (dette S6) à ajouter | Certaine | Faible | Intégrer une garde « input/bouton/modal reste sombre en dark » (action rétro S6) |

## Cérémonies

| Cérémonie | Objet |
|-----------|-------|
| Planning P1 | Sprint Goal + périmètre (ce document) |
| Planning P2 | Décomposition (`/project:decompose-tasks 007`) |
| Daily | Avancement, blocages (axe-core, couverture CI) |
| Review | Démo : rapport a11y vert (clair/dark/RTL) + CI verte + README/catalogue/CHANGELOG |
| Rétro | Directive Fondamentale — clôture du projet |

## Directive Fondamentale de la Rétrospective

> « Indépendamment de ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait le meilleur travail possible, compte tenu de ce qui était connu à ce moment-là, de ses compétences et capacités, des ressources disponibles et de la situation du moment. » — Norman Kerth

## Actions rétro Sprint 6 intégrées à ce sprint

- **Action 1** : ADR « intégration d'une lib JS via importmap » (+ manifeste `assets/package.json`) → dans US-026 (doc).
- **Action 2** : garde visuelle dark ciblée (anti-régression dette dark-mode) → US-025 ou US-026.
- **Action 3** : doc « gotchas composants » (Button attributes, `_self.macro` vs slots, `:iconStart`) → US-026 (catalogue).

---

**Prochaine étape :** `/project:decompose-tasks 007` puis développement TDD (US-025 → US-026), avec revue visuelle systématique (clair/dark/RTL).
