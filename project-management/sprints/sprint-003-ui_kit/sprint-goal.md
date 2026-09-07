# Sprint 003 — Bibliothèque de composants UI (UI Kit)

## Informations

| Attribut | Valeur |
|----------|--------|
| Numéro | 003 |
| Durée | 2 semaines (10 j) |
| Capacité | ~21-26 pts (1 dev) |
| Engagé | **19 points** |
| Prérequis | Sprints 1 & 2 ✅ (fondations + navigation interactive) |

## Sprint Goal

> **Livrer le cœur de la bibliothèque de composants UI de tailsfadmin — alertes, badges, avatars, boutons, modales et dropdowns — en Twig Components accessibles et fidèles à TailAdmin, prêts à composer n'importe quelle page admin.**

## Sprint Backlog

| Priorité | ID | Titre | Points | Statut |
|----------|-----|-------|--------|--------|
| 🔴 Must | US-008 | Composants Alerts (4 variantes) | 3 | 🔵 To Do |
| 🔴 Must | US-009 | Composants Badges & Avatars | 3 | 🔵 To Do |
| 🔴 Must | US-010 | Composants Buttons (6 variantes) | 3 | 🔵 To Do |
| 🔴 Must | US-011 | Modals / overlays accessibles (Stimulus) | 5 | 🔵 To Do |
| 🔴 Must | US-012 | Dropdowns accessibles (Twig + contrôleur existant) | 5 | 🔵 To Do |

**Total engagé : 19 points**

> **US-013** (cards, media, grid images, videos — 5 pts, présentational) **reportée au Sprint 4** (1re candidate).

## Capitalisation Sprint 2

- **US-012 allégée** : le contrôleur Stimulus `dropdown` générique **existe déjà** (créé en US-007). US-012 se limite à un/des Twig Component(s) `tsf:Ui:Dropdown` + variantes + tests.
- **Pattern modal** : réutilise l'approche `focus trap` / `Échap` déjà éprouvée sur la sidebar (drawer mobile).
- **CSS composants** : les classes TailAdmin sont désormais complètes dans `app.css` (fix CSS) — les variantes alerts/badges/buttons s'appuient dessus.

## Ordre de développement recommandé

`US-008 → US-009 → US-010` (petits, présentational) `→ US-012` (Twig sur contrôleur existant) `→ US-011` (modal, le plus complexe : focus trap)

## Definition of Ready (vérifiée)

- [x] US-008→012 : description, Gherkin (1 nominal + 2 alt + 2 err), estimation, dépendances.
- [x] Sources de référence disponibles (`Tools/sources/…/src/partials/{alert,badge,avatar,buttons}/…`, `overlay.html`).
- [x] CSS composant complet, contrôleur dropdown en place.

## Dépendances

| US | Dépend de | Statut |
|----|-----------|--------|
| US-008/009/010 | US-002 (CSS), US-004 (layout) | ✅ |
| US-011 | US-002, US-004 (nouveau contrôleur `modal`) | ✅ / à créer |
| US-012 | US-007 (contrôleur `dropdown`) | ✅ |

## Actions issues de l'incident CSS (Sprint 2) — à intégrer

- [ ] **Garde-fou 1** : smoke-test du **CSS compilé** (asserter la présence de classes composant/structurelles, ex. `.menu-item`, `flex`) pour attraper une régression de style.
- [ ] **Garde-fou 2** : **premier test navigateur** (`symfony/panther`) sur un comportement JS réel (ex. ouverture/fermeture d'une modale ou d'un dropdown) — spike.

## Risques

| Risque | Prob. | Impact | Mitigation |
|--------|-------|--------|------------|
| Modal a11y (focus trap, restitution focus, `aria-modal`) non triviale | Moyenne | Moyen | Réutiliser le pattern sidebar drawer ; test dédié |
| Régression CSS silencieuse (cf. Sprint 2) | Moyenne | Moyen | Garde-fou smoke-test CSS + revue visuelle |
| Multiplicité des variantes (6 boutons, 6 badges…) | Faible | Faible | Props/énumérations, pas de duplication (DRY) |

## Cérémonies

| Cérémonie | Objet |
|-----------|-------|
| Planning P1 | Sprint Goal + périmètre |
| Planning P2 | Décomposition (`/project:decompose-tasks 003`) |
| Daily | Avancement, blocages |
| Review | Démo : galerie de composants UI (clair/dark) |
| Rétro | Directive Fondamentale |

## Directive Fondamentale de la Rétrospective

> « Indépendamment de ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait le meilleur travail possible, compte tenu de ce qui était connu à ce moment-là, de ses compétences et capacités, des ressources disponibles et de la situation du moment. » — Norman Kerth

---

**Prochaine étape :** `/project:decompose-tasks 003` puis développement TDD.
