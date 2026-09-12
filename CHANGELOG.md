# Changelog

Toutes les évolutions notables de **tailsfadmin** sont documentées ici.

Le format s'appuie sur [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/),
et le projet suit le [Semantic Versioning](https://semver.org/lang/fr/) 2.0.0.

## [Unreleased]

### Added

- **`tsf:Ui:StatCard`** (gap G1) : carte KPI (icône via slot `icon` + label + valeur),
  avec variation optionnelle (`delta`/`trend`), barre de progression optionnelle
  (`progress`, bornée [0,100]), sous-texte (`hint`) et lien (`href`). Teintes
  `variant` brand|success|warning|error. Accessible (barre `role="progressbar"`).
- **`tsf:Layout:PageHeader`** (gap G4) : bandeau d'en-tête de page — titre `<h1>`,
  sous-titre optionnel, slot `actions` (aligné à droite) et slot `breadcrumb`.

Mobilisés par les dashboards et en-têtes du reskin (hotTwos EPIC-003, US-087).

## [1.5.0] — 2026-09-10

### Changed

- **Support PHP élargi** : la contrainte minimale passe de `>=8.5` à **`>=8.2`**
  (alignée sur Symfony 7.3, dont le minimum est PHP 8.2). Le bundle s'installe
  désormais sur PHP 8.2 → 8.5. Compatibilité validée par PHPStan (plage 8.2→8.5),
  un contrôle d'installabilité runtime en 8.2 et l'exécution des tests en 8.3/8.4
  (la suite PHPUnit 12/13 requiert PHP ≥ 8.3). Débloque la recette Flex
  (symfony/recipes-contrib).

## [1.4.2] — 2026-09-10

### Fixed

- **Onglets — surlignage de l'onglet actif (`tsf:Ui:Tabs`)** : le contrôleur
  `tailsfadmin--tabs` basculait bien `aria-selected` et l'affichage des panneaux,
  mais les classes visuelles actives étaient figées au rendu Twig — le surlignage
  (soulignement / pastille) ne suivait pas la sélection. Il est désormais piloté par
  la variante Tailwind `aria-selected:` (les 4 variantes underline/segmented/pill/boxed).

## [1.4.1] — 2026-09-10

### Fixed

- **Sidebar repliée (collapse runtime)** : la classe `.sidebar-collapsed` posée par
  le contrôleur `tailsfadmin--sidebar#toggle` n'avait aucune règle CSS associée ; le
  repli n'était donc pas fonctionnel (libellés non masqués). `.sidebar-collapsed`
  partage désormais le rendu « icônes seules » de `.sidebar-mini` (libellés,
  titres de groupe et logo texte masqués en `sr-only`, conservés dans le DOM).
- **Révélation des libellés au survol** : `.sidebar:hover` ne rétablissait que
  `display` sans annuler le clipping `sr-only` (`position`/`clip`/`width`), si bien
  que les libellés d'une sidebar repliée (mini ou collapsed) ne réapparaissaient pas
  visuellement au survol. Les libellés et titres de groupe sont maintenant
  ré-affichés pleinement (position statique, clip annulé).

## [1.4.0] — 2026-09-09

### Added

- **Commande `make:tailsfadmin-page`** : génère une page conforme au thème
  (contrôleur + template étendant `@Tailsfadmin/layout/admin.html.twig`) depuis un
  gabarit `blank | dashboard | table | form`. Non-interactive, `--force`, gabarit
  inconnu rejeté avec la liste des choix. Code généré valide (PSR-12 / PHPStan).
  Ex. `php bin/console make:tailsfadmin-page Sales --template=dashboard` (US-035).
- **Écrans auth & utilitaires étendus** (`/auth/reset-password`, `/auth/new-password`,
  `/auth/otp`, `/auth/success`, `/auth/maintenance`, `/auth/coming-soon`, `/auth/500`) :
  extension d'US-023 réutilisant le layout auth centré. Nouveau contrôleur Stimulus
  **`tailsfadmin--otp`** (saisie de code segmentée : auto-focus, Backspace, collage).
  Page d'erreur **500** métier (override TwigBundle, sans stack trace en prod) (US-034).
- **Pages type applicatives de démo** (`/app/{settings,pricing,invoice,chat,files,inbox}`) :
  Settings (onglets segmentés + formulaires), Pricing (grille de plans + Ribbon),
  Invoice (facture imprimable), Chat (liste + fil), File manager (Dropzone + grille),
  Inbox (liste + volet de lecture). Assemblage de composants existants, données
  statiques. Menu + i18n fr/en/ar (US-033).
- **Dashboards métier de démo** (`/dashboards` + `/dashboards/{analytics,marketing,crm,saas}`) :
  quatre tableaux de bord assemblés depuis le bundle (KPI, graphiques ApexCharts,
  tables, Badge, ProgressBar). Données statiques. Menu + i18n fr/en/ar (US-032).
- **`tsf:Ui:Tabs` — variante `segmented`** : conteneur gris pleine largeur avec
  pastille blanche active (style « Default » TailAdmin) et **rendu des icônes**
  par onglet (`item.icon`, via le registre `tsf_icon`). Vitrine `/ui-kit` mise à
  jour avec les 3 styles (segmenté, souligné, souligné + icônes).

## [1.3.0] — 2026-09-09

### Added

- **Composants d'affichage** : `tsf:Ui:Tabs` (pattern ARIA « Tabs » + contrôleur
  Stimulus `tailsfadmin--tabs`, navigation clavier), `tsf:Ui:ProgressBar`
  (clamp [0,100], `role="progressbar"`) et `tsf:Ui:Ribbon` (coin / arrondi).
  Dégradation gracieuse (clamp / fallback, sans exception) (US-036).
- **Variantes d'agencement du layout (6)** : le layout admin expose de nouveaux
  blocs surchargeables (`layout_shell_class`, `layout_shell_attributes`, `main_class`)
  permettant de dériver des agencements sans dupliquer le shell. Le composant
  `tsf:Layout:Sidebar` accepte une prop **`mini`** (sidebar icônes, libellés
  accessibles en sr-only). Galerie de démo `/layouts` : sidebar extensible,
  mini-sidebar, navigation horizontale, contenu boxed, sidebar à droite, en-tête
  double niveau. Menu + i18n fr/en/ar (US-037).
- **Contrôleur Stimulus `tailsfadmin--kanban`** : tableau Kanban avec glisser-déposer
  **HTML5 natif** (aucune lib / CDN), compteurs par colonne mis à jour au déplacement,
  **alternative clavier** (« Déplacer vers … ») et annonces **`aria-live`**. Déclaré
  dans les deux `package.json` (US-040).
- **Pages de démo « Tâches »** (`/tasks`, `/tasks/kanban`) : vue **liste**
  filtrable/triable côté serveur (sans JS) et vue **Kanban** à 4 colonnes avec
  cartes déplaçables (Avatar, Badge). Données factices. Menu + i18n fr/en/ar (US-040).
- **Contrôleur Stimulus `tailsfadmin--clipboard`** : copie une valeur dans le
  presse-papiers avec retour visuel et dégradation gracieuse (repli
  `execCommand` hors contexte sécurisé). Déclaré dans les deux `package.json` (US-039).
- **Page de démo « Intégrations / Clés d'API »** (`/integrations`) : liste de clés
  factices masquées, révélation en CSS peer (sans JS), copie en un clic, génération
  et révocation via `tsf:Ui:Modal`, le tout organisé en onglets (`tsf:Ui:Tabs`). La
  clé en clair n'est affichée qu'une seule fois. Données strictement factices,
  stockées en session (aucune base, aucun secret réel) (US-039).
- **Page de démo « Mise en page de formulaire »** (`/forms/layout`) : deux gabarits
  prêts à copier — une colonne (formulaire compact) et sectionné (grille deux
  colonnes responsive avec repli mobile + barre d'actions cohérente). Réutilise le
  thème de formulaire et `tsf:Ui:Card`, sans nouveau widget (US-038).

### Fixed

- **Thème de formulaire — accessibilité** : les aides de champ (option `help`) sont
  désormais reliées au champ via `aria-describedby` (parité avec le `form_row` natif
  de Symfony). Le `form_row` surchargé ne transmettait que `aria-invalid` et perdait
  ce lien ; tous les formulaires du bundle en bénéficient.

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

[Unreleased]: https://github.com/thibmonier/tailsfadmin/compare/v1.5.0...HEAD
[1.5.0]: https://github.com/thibmonier/tailsfadmin/compare/v1.4.2...v1.5.0
[1.4.2]: https://github.com/thibmonier/tailsfadmin/compare/v1.4.1...v1.4.2
[1.4.1]: https://github.com/thibmonier/tailsfadmin/compare/v1.4.0...v1.4.1
[1.4.0]: https://github.com/thibmonier/tailsfadmin/compare/v1.3.0...v1.4.0
[1.3.0]: https://github.com/thibmonier/tailsfadmin/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/thibmonier/tailsfadmin/compare/v1.1.0...v1.2.0
[1.0.0]: https://github.com/thibmonier/tailsfadmin/releases/tag/v1.0.0
