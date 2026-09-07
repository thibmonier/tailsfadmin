# PRD — tailsfadmin

**Product Requirements Document**
**Version :** 1.0.0 · **Date :** 2026-09-07 · **Statut :** Draft · **Auteur :** Product Owner

---

## 1. Résumé exécutif

**tailsfadmin** est un thème d'administration (dashboard/back-office) pour **Symfony 8.1**, fortement inspiré de **TailAdmin**. Il porte le design system TailAdmin dans l'écosystème **Symfony UX** de manière idiomatique : composants **Twig Components / Live Components**, interactivité en **Stimulus** (conversion d'Alpine.js), assets via **AssetMapper**, styles en **Tailwind CSS v4**, servi via **FrankenPHP** sous **PHP 8.5**.

Le produit est livré sous **deux formes complémentaires** :
1. Un **bundle Symfony réutilisable** (composants, layout, assets, contrôleurs Stimulus) installable via Composer/AssetMapper.
2. Une **application de démonstration** qui consomme le bundle et présente l'ensemble des pages et composants.

---

## 2. Contexte & problème

Les développeurs Symfony qui doivent bâtir un back-office repartent souvent d'une page blanche ou adaptent péniblement des templates HTML/React non idiomatiques. Les thèmes admin de qualité (TailAdmin) existent pour Tailwind pur, React, Next.js et Laravel — **mais pas pour Symfony UX**.

**Sources disponibles** (extraites dans `Tools/sources/`) :
- `tailadmin-free-tailwind-dashboard-template-main/` — **source primaire** (HTML/Tailwind v4 pur, ~20 pages, partials par composant).
- `tailadmin-laravel-main/` — **référence serveur** (composants Blade, menu/sidebar via `MenuHelper`/`SidebarController`, i18n).
- `free-react-tailwind-admin-dashboard-main/` et `free-nextjs-admin-dashboard-main/` — références secondaires.

**Opportunité** : offrir aux équipes Symfony un kit admin fidèle à TailAdmin, accessible, thématisable et maintenu, exploitant pleinement Symfony UX.

---

## 3. Vision produit

> Pour les **développeurs Symfony** qui doivent livrer un back-office soigné rapidement,
> **tailsfadmin** est un thème d'administration
> qui fournit un design system TailAdmin idiomatique Symfony UX (Twig Components + Stimulus + AssetMapper),
> contrairement aux templates HTML/React à adapter à la main,
> notre produit s'installe via Composer et se compose en Twig, avec dark mode, responsive et accessibilité intégrés.

---

## 4. Objectifs & KPI

| Objectif | Métrique de succès | Cible |
|----------|--------------------|-------|
| Installation rapide | Temps d'un dashboard fonctionnel après `composer require` | < 30 min |
| Fidélité visuelle | Écart visuel vs TailAdmin (revue design par page) | ≥ 95 % conforme |
| Idiomatique Symfony | Part de l'interactivité en Stimulus/UX (0 Alpine.js résiduel) | 100 % |
| Accessibilité | Conformité WCAG AA sur les pages de démo | 100 % pages |
| Dark mode & responsive | Pages validées clair/dark et mobile/desktop | 100 % pages |
| Qualité | PHPStan max sans erreur, couverture tests PHP | ≥ 80 % |
| Réutilisabilité | Composants consommables hors app de démo | 100 % |

---

## 5. Personas

Voir `personas.md`. Résumé :
- **P-001** Développeur Symfony intégrateur (primaire) — consomme le bundle.
- **P-002** Lead / Designer UI — fidélité, tokens, dark mode, a11y.
- **P-003** Mainteneur / contributeur — architecture, tests, doc, versioning.
- **P-004** Utilisateur final de l'admin — usabilité, responsive, performance.

---

## 6. Périmètre

### 6.1 Dans le périmètre (In scope)

- **Fondations** : bundle Symfony + app de démo, AssetMapper/importmap, Tailwind v4 intégré, pipeline FrankenPHP/PHP 8.5.
- **Layout & chrome** : layout admin (sidebar, header, content, breadcrumb, preloader), **dark mode**, **sidebar** responsive (collapse/mobile).
- **Composants UI** (portage TailAdmin) : alerts, avatars, badges, buttons, dropdowns, modals/overlays, cards, breadcrumb, grilles d'images, media cards.
- **Tables** : tables basiques et avancées.
- **Formulaires** : inputs, selects, checkboxes/radios, toggles, textarea, **datepicker (flatpickr)**, **upload (Dropzone)**, groupes/états.
- **Data-viz** : graphiques **ApexCharts** (line, bar, etc.), **carte vectorielle** (jsvectormap).
- **Calendrier** : **FullCalendar** avec modal d'événement.
- **Pages** : dashboard e-commerce, profil, calendrier, tables, formulaires, UI kit (alerts/badges/buttons/avatars…), pages d'authentification (signin/signup), page blank, 404.
- **i18n** : internationalisation (au moins FR/EN) inspirée de la version Laravel.
- **Carrousels** : **Swiper** (si présent dans les composants portés).
- **Accessibilité** transversale et **documentation** d'usage/contribution.

### 6.2 Hors périmètre (Out of scope)

- Backend métier réel (CRUD applicatif, entités de domaine autres que démo/auth minimale).
- Système d'authentification production complet (SSO, MFA) — les pages auth sont **UI de démo**.
- Gestion des rôles/permissions applicative (au-delà d'un exemple).
- Versions React/Next.js (uniquement en référence).
- Back-office multi-tenant, facturation, etc.

---

## 7. Exigences fonctionnelles (FR)

| ID | Exigence | Persona | Priorité (MoSCoW) |
|----|----------|---------|-------------------|
| FR-01 | Bundle Symfony installable + app de démo démarrable (FrankenPHP/PHP 8.5) | P-001, P-003 | Must |
| FR-02 | Intégration AssetMapper + Tailwind v4 (build CSS, importmap) | P-001, P-003 | Must |
| FR-03 | Layout admin (sidebar + header + content + breadcrumb + preloader) | P-001, P-004 | Must |
| FR-04 | Dark mode global persistant, cohérent sur tous les composants | P-002, P-004 | Must |
| FR-05 | Sidebar responsive : collapse desktop + drawer mobile, navigation multi-niveaux | P-004 | Must |
| FR-06 | Dashboard e-commerce (métriques, graphiques, cibles, commandes récentes, carte) | P-004 | Must |
| FR-07 | Bibliothèque de composants UI (alerts, avatars, badges, buttons, dropdowns, cards) | P-001, P-002 | Must |
| FR-08 | Modals / overlays accessibles (focus trap, Échap) | P-002, P-004 | Must |
| FR-09 | Tables basiques et avancées | P-004 | Should |
| FR-10 | Formulaires : inputs, selects, checkbox/radio, toggle, textarea, états | P-001, P-004 | Must |
| FR-11 | Datepicker (flatpickr) en contrôleur Stimulus | P-001, P-004 | Should |
| FR-12 | Upload de fichiers (Dropzone) en contrôleur Stimulus | P-001, P-004 | Should |
| FR-13 | Graphiques ApexCharts (line/bar…) en contrôleur Stimulus | P-004 | Must |
| FR-14 | Carte vectorielle (jsvectormap) | P-004 | Could |
| FR-15 | Calendrier FullCalendar + modal d'événement | P-004 | Should |
| FR-16 | Pages d'authentification (signin/signup) — UI de démo | P-004 | Should |
| FR-17 | Page profil (infos, adresse, modals d'édition) | P-004 | Should |
| FR-18 | Pages utilitaires : blank, 404 | P-001 | Should |
| FR-19 | Internationalisation (FR/EN) avec sélecteur de langue | P-004 | Should |
| FR-20 | Carrousels Swiper (si composant porté) | P-004 | Could |
| FR-21 | Documentation d'installation, d'usage des composants et de contribution | P-001, P-003 | Must |

---

## 8. Exigences non fonctionnelles (NFR)

| ID | Exigence | Cible |
|----|----------|-------|
| NFR-01 | **Fidélité visuelle** à TailAdmin | ≥ 95 % par page (revue design) |
| NFR-02 | **Accessibilité** | WCAG AA (clair + dark), navigation clavier |
| NFR-03 | **Responsive** | Mobile / tablette / desktop |
| NFR-04 | **Qualité PHP** | PHPStan niveau max, PSR-12, SOLID |
| NFR-05 | **Tests** | Couverture ≥ 80 % code PHP, tests composants + fonctionnels |
| NFR-06 | **Performance front** | Assets optimisés via AssetMapper, pas de JS mort |
| NFR-07 | **Idiomatique** | Interactivité 100 % Stimulus/UX (0 Alpine.js) |
| NFR-08 | **Maintenabilité** | Séparation nette bundle/démo, SemVer, CHANGELOG |
| NFR-09 | **Compatibilité** | Symfony 8.1, PHP 8.5, FrankenPHP, Tailwind v4 |
| NFR-10 | **Sécurité** | Headers de sécurité, pas de secret en dur, en-têtes CSP compatibles assets |

---

## 9. Contraintes techniques

- **Framework** : Symfony 8.1 · **PHP** : 8.5 · **Runtime** : FrankenPHP.
- **Assets** : AssetMapper + importmap (pas de Webpack Encore imposé).
- **UI** : Symfony UX — Twig Components, Live Components, Stimulus.
- **CSS** : Tailwind CSS v4 (nouveau moteur ; intégration avec AssetMapper = **point de conception critique**, à trancher en phase Design via ADR).
- **Libs JS à intégrer** (via Stimulus/importmap) : ApexCharts, FullCalendar, flatpickr, Dropzone, jsvectormap, Swiper.
- **Portage** : conversion **Alpine.js → Stimulus/UX** (décision actée à l'init).

---

## 10. Risques & hypothèses

| Risque | Impact | Mitigation |
|--------|--------|------------|
| Tailwind v4 mal intégré à AssetMapper | Élevé | ADR dédié en phase Design ; POC dès le Sprint 1 (walking skeleton) |
| Conversion Alpine → Stimulus coûteuse | Moyen | Prioriser les comportements structurants (sidebar, dark mode, modals) ; wrappers Stimulus réutilisables |
| Distribution d'assets depuis un bundle | Moyen | ADR packaging (assets bundle + `importmap:require`), tester la conso hors démo |
| Fidélité visuelle vs re-branding | Moyen | Design tokens centralisés dès le layout |
| Dérive de périmètre (UI kit très large) | Moyen | MoSCoW strict ; Could/Would en fin de backlog |

**Hypothèses** : les sources TailAdmin (licence MIT) sont réutilisables ; l'équipe est composée d'un développeur ; pas de backend métier réel attendu au-delà de la démo.

---

## 11. Découpage EPIC (aperçu — détaillé dans le backlog)

- **EPIC-001** — Fondations & socle technique (bundle + démo, AssetMapper, Tailwind v4, FrankenPHP).
- **EPIC-002** — Layout & navigation (sidebar, header, dark mode, breadcrumb).
- **EPIC-003** — Bibliothèque de composants UI (alerts, badges, buttons, avatars, cards, modals, dropdowns).
- **EPIC-004** — Formulaires & tables (inputs, datepicker, upload, tables).
- **EPIC-005** — Data-viz & calendrier (ApexCharts, jsvectormap, FullCalendar).
- **EPIC-006** — Pages applicatives (dashboard, profil, auth, blank, 404) & i18n.
- **EPIC-007** — Qualité, accessibilité & documentation.

> Le **Sprint 1 = Walking Skeleton** : app de démo qui démarre + layout minimal + dark mode + un composant + Tailwind v4/AssetMapper opérationnels, prouvant la chaîne technique de bout en bout.

---

**Prochaine étape :** génération du backlog (`/project:generate-backlog`) puis phase de Conception (`/workflow:design`, ADRs).
