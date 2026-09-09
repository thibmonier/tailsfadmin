# Changelog

Toutes les évolutions notables de **tailsfadmin** sont documentées ici.

Le format s'appuie sur [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/),
et le projet suit le [Semantic Versioning](https://semver.org/lang/fr/) 2.0.0.

## [Unreleased]

## [1.2.0] — 2026-09-09

### Added

- **Menu filtrable par permission** : clé optionnelle `permission` sur les items
  et sous-items du menu (`tailsfadmin.yaml`). Quand un composant de sécurité est
  disponible, le `MenuBuilder` masque les items dont l'utilisateur courant n'a pas
  l'autorisation (`is_granted`) ; le checker est injecté de façon optionnelle
  (`@?security.authorization_checker`) — **aucune dépendance dure**, tout reste
  visible sans sécurité (rétro-compatible).
- **Header — slots surchargeables** : le composant `tsf:Layout:Header` expose des
  blocs nommés `language`, `notifications` et `user_menu`, surchargeables via
  `<twig:block name="…">` — pour brancher un vrai menu utilisateur (déconnexion +
  CSRF), un flux de notifications réel, ou retirer le sélecteur de langue lorsque
  l'application ne définit pas de route `locale_switch`.

### Changed

- **Menu** : un groupe dont tous les items sont filtrés par permission n'est plus
  rendu (auparavant l'en-tête de groupe vide subsistait).

## [1.0.0] — 2026-09-08

Première version stable : thème d'administration Symfony inspiré de TailAdmin,
livré comme **bundle réutilisable** + **application de démonstration**.

### Added

- **Fondations** (Sprint 1) : bundle Symfony 8.1 / PHP 8.5, layout admin
  `@Tailsfadmin/layout/admin.html.twig`, Tailwind CSS v4 sans Node
  (symfonycasts/tailwind-bundle), runtime FrankenPHP, CI GitHub Actions.
- **Navigation & layout** (Sprint 2) : dark mode persistant (anti-FOUC), sidebar
  responsive configurable (MenuBuilder), header (recherche Cmd+K, dropdowns).
- **UI Kit** (Sprint 3) : `tsf:Ui:Alert`, `Badge`, `Avatar`, `Button`, `Dropdown`,
  `Modal` (dialog accessible, focus trap).
- **Cards, formulaires & tables** (Sprint 4) : `tsf:Ui:Card`/`MediaCard`/`GridImage`,
  composants `tsf:Form:*` + form theme Symfony, `tsf:Ui:Table`/`TableAdvanced`.
- **Data-viz & widgets** (Sprint 5) : contrôleurs Stimulus wrappers pour ApexCharts
  (`tsf:Chart:Line`/`Bar`), FullCalendar, flatpickr (`tsf:Form:Datepicker`),
  Dropzone (`tsf:Form:Upload`) — vendorés via importmap, sans CDN, dark-aware.
- **Pages applicatives** (Sprint 6) : dashboard e-commerce, page profil + modales
  d'édition, pages d'authentification (signin/signup) + utilitaires (page vierge,
  404 sans stack en prod).
- **Carte vectorielle** : `tsf:Chart:VectorMap` (jsvectormap) pour la démographie.
- **Internationalisation** (Sprint 6) : `LocaleSubscriber`, catalogues FR/EN
  (chrome + menu), structure AR (RTL) / ES / DE, sélecteur de langue, `<html dir>`
  dynamique, helpers Twig `tsf_dir()` / `tsf_locales()`.
- **Accessibilité** (Sprint 7) : conformité WCAG 2.2 AA (audit axe-core automatisé
  en E2E, clair + dark), sous-menus sidebar repliables (`aria-expanded`),
  indicateur de focus clavier visible.
- **Qualité & livrabilité** (Sprint 7) : CI étendue (couverture ≥ 80 %, E2E + axe,
  lint front Biome), documentation d'installation et catalogue de composants.

### Changed

- Bouton primaire : fond `dark:bg-brand-600` en dark (contraste du texte blanc AA).
- Badges : texte `-700` en clair / `-400` en dark (contraste AA).
- `tsf:Ui:Button` : ajout de la prop `block` (pleine largeur).

### Fixed

- **Dark mode** : retrait de l'inversion de l'échelle de gris (approche TailAdmin) —
  les surfaces `dark:bg-gray-*` restaient claires en sombre.
- **Modale** : focus trap corrigé (accès invalide à un champ privé statique).
- **Dropdown** : `aria-expanded` déplacé du `<div>` wrapper vers le `<button>`
  (attribut ARIA valide).
- **Select** : double chevron, `select multiple`, ouverture des dropdowns.

### Security

- Bascule de langue : redirection restreinte au même hôte (anti open-redirect).
- Pages 404 en production sans exposition de stack trace.

[Unreleased]: https://github.com/thibmonier/tailsfadmin/compare/v1.2.0...HEAD
[1.2.0]: https://github.com/thibmonier/tailsfadmin/compare/v1.1.0...v1.2.0
[1.0.0]: https://github.com/thibmonier/tailsfadmin/releases/tag/v1.0.0
