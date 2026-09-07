# US-007 — Header avec recherche (Cmd/Ctrl+K), dropdowns utilisateur et notifications

**EPIC :** EPIC-002-layout-navigation · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Should · **Sprint :** backlog (Sprint 2)

## Carte (Card)
> En tant que **P-004 — Utilisateur administrateur**, je veux **un header complet avec une recherche rapide activable au clavier (Cmd/Ctrl+K et « / »), un dropdown de profil utilisateur et un dropdown de notifications**, afin de **accéder rapidement aux fonctionnalités transversales de l'administration sans quitter la page en cours**.

## Conversation
Le header est la barre supérieure fixe présente sur toutes les pages. Sources : `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/header.html` (structure complète : logo, hamburger, barre de recherche, notifications, user menu) et `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/js/index.js` (raccourcis clavier `Cmd/Ctrl+K` et `/` pour ouvrir la recherche, logique d'ouverture/fermeture des dropdowns). Sources référence serveur : `Tools/sources/tailadmin-laravel-main/resources/views/components/header/` (user-info, notifications). Toute l'interactivité Alpine (`x-show`, `@click.outside`, `x-transition`, `@keydown`) est convertie en contrôleurs Stimulus du bundle : `dropdown_controller.js` (générique, réutilisable pour user-menu et notifications), `search_controller.js` (ouverture modale/inline, raccourcis clavier). Exigences accessibilité : chaque dropdown est un `role="menu"` avec items `role="menuitem"`, fermeture à Échap, fermeture au clic extérieur (Stimulus `clickOutside`), navigation clavier flèches haut/bas. Le breadcrumb affiché dans le header reprend le composant de US-004. La recherche à ce stade est une coquille visuelle (pas de backend de recherche) ; le focus et les raccourcis clavier doivent être fonctionnels. L'avatar utilisateur et les compteurs de notifications sont injectés via des variables Twig configurables dans la démo.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Header avec raccourci de recherche et dropdowns accessibles
  Scenario: L'utilisateur ouvre la recherche via le raccourci clavier
    Given la page "/" est chargée et aucun champ de formulaire n'a le focus
    When l'utilisateur appuie sur "Ctrl+K" (ou "Cmd+K" sur macOS)
    Then la modale ou le champ de recherche s'ouvre et reçoit le focus automatiquement
    And l'arrière-plan de la page est assombri ou la modale est au premier plan
    When l'utilisateur appuie sur "Échap"
    Then la recherche se ferme et le focus retourne à l'élément déclencheur
```
### Scénarios alternatifs
```gherkin
  Scenario: L'utilisateur ouvre le dropdown de profil et navigue au clavier
    Given la page "/" est chargée
    When l'utilisateur clique sur l'avatar utilisateur dans le header
    Then le dropdown de profil s'ouvre avec les options (Profil, Paramètres, Déconnexion)
    And l'attribut "aria-expanded" de l'avatar passe à "true"
    When l'utilisateur appuie sur la flèche bas
    Then le focus se déplace sur le premier item du dropdown "Profil"
    When l'utilisateur appuie sur "Échap"
    Then le dropdown se ferme et le focus retourne sur l'avatar

  Scenario: Le raccourci "/" ouvre également la recherche depuis le corps de la page
    Given la page "/" est chargée et le focus n'est pas dans un champ de texte
    When l'utilisateur appuie sur la touche "/"
    Then la recherche s'ouvre avec le même comportement que Cmd/Ctrl+K
    And l'événement de frappe "/" n'est pas inséré dans le champ de recherche
```
### Scénarios d'erreur
```gherkin
  Scenario: Le dropdown de notifications reste ouvert après un clic en dehors
    Given le dropdown de notifications est ouvert
    When l'utilisateur clique à l'extérieur de la zone du dropdown
    Then le dropdown se ferme automatiquement
    And l'attribut "aria-expanded" du bouton de notifications passe à "false"
    And aucun autre dropdown n'est impacté

  Scenario: Le raccourci Cmd/Ctrl+K est déclenché alors qu'un champ de formulaire a le focus
    Given le curseur est positionné dans un champ "input[type=text]" de la page
    When l'utilisateur appuie sur "Ctrl+K"
    Then la recherche NE s'ouvre PAS (le raccourci est ignoré)
    And aucun comportement du navigateur par défaut n'est interféré
```

## INVEST
- **Independent :** Dépend de US-004 (layout) ; indépendante de la sidebar (US-006).
- **Negotiable :** Le backend de recherche (autocomplétion, résultats réels) est hors périmètre et négociable pour un sprint ultérieur.
- **Valuable :** Un header sans raccourcis clavier ni dropdowns accessibles dégrade significativement l'expérience admin au quotidien.
- **Estimable :** 8 points maximum ; conversion Alpine + accessibilité clavier + raccourcis = travail substantiel.
- **Small :** La recherche est une coquille visuelle ; pas de backend. Les dropdowns ne gèrent pas les données réelles (profil, notifications proviennent de variables Twig).
- **Testable :** Raccourcis testables avec Playwright/Panther, attributs ARIA vérifiables, fermeture Échap/clic extérieur automatisable.

## Dépendances
- **Dépend de :** US-004
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md` (fidélité TailAdmin, dark mode, responsive, a11y WCAG AA, PHPStan max, tests ≥80%, Stimulus, séparation bundle/démo, doc).
