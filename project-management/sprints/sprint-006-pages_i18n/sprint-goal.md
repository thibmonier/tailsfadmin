# Sprint 006 — Pages applicatives & internationalisation

## Informations

| Attribut | Valeur |
|----------|--------|
| Numéro | 006 |
| Durée | 2 semaines (10 j) |
| Capacité | ~19-24 pts (1 dev) |
| Engagé | **23 points** |
| Prérequis | Sprints 1-5 ✅ (fondations, nav, UI kit, formulaires/tables, data-viz) |

## Sprint Goal

> **Donner vie au thème comme un vrai back-office : assembler un dashboard e-commerce complet à partir des composants existants, livrer les pages profil et authentification, et internationaliser l'interface (FR/EN + structure RTL).**

C'est le sprint d'**assemblage** : peu de nouveaux composants, on compose les pages phares de TailAdmin avec la bibliothèque déjà construite.

## Sprint Backlog

| Priorité | ID | Titre | Points | Statut |
|----------|-----|-------|--------|--------|
| 🔴 Must | US-021 | Dashboard e-commerce (métriques, cibles, ventes, commandes, carte) | 5 | 🔵 To Do |
| 🟡 Should | US-022 | Page profil + modals d'édition (infos, adresse) | 5 | 🔵 To Do |
| 🟡 Should | US-023 | Pages d'authentification + utilitaires (signin, signup, blank, 404) | 5 | 🔵 To Do |
| 🟡 Should | US-024 | i18n FR/EN + structure RTL + sélecteur de langue | 8 | 🔵 To Do |

**Total engagé : 23 points**

> **US-019 (carte jsvectormap, Could, 5 pts)** — reste candidate : US-021 en a besoin (démographie client). Si `jsvectormap` s'intègre vite (pattern wrapper connu), on l'ajoute dans le cadre d'US-021 ; sinon la carte est remplacée par un placeholder et US-019 reste au backlog.

## Ordre de développement recommandé

`US-021 (dashboard — assemble charts/tables/cards/badges) → US-022 (profil, réutilise modals) → US-023 (auth/utilitaires) → US-024 (i18n + RTL, transversal)`

## Capitalisation (assemblage) — tout est déjà là

- **US-021** assemble : `tsf:Chart:*` (US-018), `tsf:Ui:Table`/`TableAdvanced` (US-017), `tsf:Ui:Card`/`Badge`/`Avatar` (US-009/013), carte (US-019 si intégrée).
- **US-022** réutilise `tsf:Ui:Modal` (US-011) pour l'édition, `tsf:Ui:Avatar`, les composants form (US-014).
- **US-023** réutilise le layout (US-004), les composants form + form theme (US-014), buttons (US-010).
- **US-024** s'appuie sur le layout/sidebar (classes `ltr:`/`rtl:` déjà posées) et le sélecteur dans le header (US-007). Décision : composant Translation + EventSubscriber (ADR-005).

## Definition of Ready (vérifiée)

- [x] US-021/022/023/024 : description, Gherkin, estimation, dépendances.
- [x] Sources : `src/index.html` (dashboard), `src/profile.html`, `src/signin.html`, `src/signup.html`, `src/blank.html`, `src/404.html` ; Laravel `SetLocale`/`LocaleController` (i18n, 4 langues dont ar RTL).
- [x] Bibliothèque de composants complète et corrigée (fix Sprint 5).

## Dépendances

| US | Dépend de | Statut |
|----|-----------|--------|
| US-021 | US-018 (charts), US-017 (tables), US-009/013 (cards/badges), US-019 (carte, optionnel) | ✅ / carte optionnelle |
| US-022 | US-011 (modals), US-014 (form) | ✅ |
| US-023 | US-004 (layout), US-014 (form), US-010 (buttons) | ✅ |
| US-024 | US-004/007 (layout + header/sélecteur), ADR-005 | ✅ |

## Risques

| Risque | Prob. | Impact | Mitigation |
|--------|-------|--------|------------|
| Catalogues de traduction absents des sources (à recréer) | Certaine | Moyen | Créer `translations/` en/fr complets ; ar/es/de en structure (ADR-005) |
| Combinatoire dark × RTL non testée | Moyenne | Moyen | Tester une page en `dir="rtl"` (action reportée depuis rétros précédentes) |
| Carte jsvectormap (US-019) chronophage | Moyenne | Faible | Placeholder si débordement ; US-019 reste au backlog |
| Régressions visuelles (revue = seul filet fiable) | Moyenne | Moyen | Revue au navigateur + screenshots systématiques (leçon Sprint 5) |

## Cérémonies

| Cérémonie | Objet |
|-----------|-------|
| Planning P1 | Sprint Goal + périmètre |
| Planning P2 | Décomposition (`/project:decompose-tasks 006`) |
| Daily | Avancement, blocages (i18n, carte) |
| Review | Démo : dashboard e-commerce + profil + auth + bascule de langue |
| Rétro | Directive Fondamentale |

## Directive Fondamentale de la Rétrospective

> « Indépendamment de ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait le meilleur travail possible, compte tenu de ce qui était connu à ce moment-là, de ses compétences et capacités, des ressources disponibles et de la situation du moment. » — Norman Kerth

---

**Prochaine étape :** `/project:decompose-tasks 006` puis développement TDD (avec revue visuelle systématique).
