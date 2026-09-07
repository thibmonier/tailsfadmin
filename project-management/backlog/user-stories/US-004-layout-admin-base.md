# US-004 — Layout admin de base (header + sidebar + zone contenu + preloader + breadcrumb)

**EPIC :** EPIC-002-layout-navigation · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** 1

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur** et **P-004 — Utilisateur administrateur**, je veux **un layout admin de base composé d'un header, d'une sidebar, d'une zone de contenu principale, d'un preloader et d'un breadcrumb**, afin de **disposer d'une structure Twig réutilisable dont héritent toutes les pages de l'administration sans dupliquer le chrome**.

## Conversation
Le layout est le composant structurant de toutes les pages admin. Il s'appuie sur les sources HTML suivantes dans `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/` : `header.html` (barre supérieure), `sidebar.html` (navigation latérale), `preloader.html` (spinner affiché jusqu'au chargement complet), et `breadcrumb.html` (fil d'Ariane). Ces partials sont convertis en blocs Twig (`{% block header %}`, `{% block sidebar %}`, `{% block content %}`, `{% block breadcrumb %}`) dans un template `@tailsfadmin/layout/admin.html.twig` exposé par le bundle. Les composants Twig Components (Symfony UX) sont utilisés pour header et sidebar afin de préparer la réactivité Stimulus des US suivantes. Le preloader utilise un Stimulus controller `preloader` qui masque le spinner dès `window.load`. La zone de contenu est un `<main>` avec `id="main-content"` pour l'accessibilité (skip-link). Le breadcrumb expose un bloc Twig `{% block breadcrumb_items %}` pour injection depuis les pages filles. Les classes Tailwind doivent reproduire fidèlement le fichier source (marges, hauteurs, z-index). Dark mode : les tokens CSS `--color-*` définis en US-002 s'appliquent automatiquement via la classe `.dark` sur `<html>`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Layout admin de base rendu dans la démo
  Scenario: La page d'accueil de la démo affiche le layout complet
    Given la démo est démarrée et le CSS compilé (US-002)
    When le navigateur charge la route "/"
    Then la page contient un élément "<header>" avec la classe "header"
    And la page contient un élément "<aside>" avec la classe "sidebar"
    And la page contient un élément "<main id='main-content'>"
    And le preloader est présent dans le DOM puis masqué après chargement complet
    And le breadcrumb affiche "Dashboard" comme premier élément
```
### Scénarios alternatifs
```gherkin
  Scenario: Une page fille hérite du layout et injecte son propre contenu
    Given un template Twig enfant étend "@tailsfadmin/layout/admin.html.twig"
    When ce template définit "{% block content %}" avec du contenu personnalisé
    Then le navigateur affiche le header, la sidebar et le contenu personnalisé sans duplication
    And les blocs header et sidebar sont identiques à ceux de la page d'accueil

  Scenario: Le skip-link est fonctionnel au clavier
    Given la page "/" est chargée
    When l'utilisateur appuie sur la touche Tab en premier focus
    Then un lien "Aller au contenu principal" devient visible
    And l'activation du lien déplace le focus sur "main#main-content"
```
### Scénarios d'erreur
```gherkin
  Scenario: Template "@tailsfadmin/layout/admin.html.twig" introuvable
    Given le bundle n'est pas enregistré dans "config/bundles.php" de la démo
    When Symfony tente de rendre une page héritant du layout
    Then Twig lève une exception "Template not found" avec le chemin attendu
    And le message d'erreur mentionne "TailsfadminBundle"

  Scenario: Absence du CSS compilé (US-002 non réalisée)
    Given le fichier CSS n'a pas été compilé dans "public/assets/"
    When le navigateur charge la route "/"
    Then la page se charge sans mise en forme
    And la console navigateur affiche une erreur 404 sur la feuille de style
    And aucune erreur PHP n'est levée (dégradation gracieuse)
```

## INVEST
- **Independent :** Dépend de US-002 (CSS) et US-003 (serveur) mais est indépendante des composants fonctionnels (US-006, US-007).
- **Negotiable :** La profondeur du découpage en Twig Components vs simples includes est négociable en sprint planning.
- **Valuable :** Sans layout, aucune page admin ne peut être développée ni démontrée.
- **Estimable :** 5 points ; conversion HTML→Twig connue, mais intégration Twig Components + accessibilité add du volume.
- **Small :** Périmètre structure uniquement ; pas d'interactivité sidebar (US-006) ni dropdowns header (US-007).
- **Testable :** Éléments DOM vérifiables, skip-link testable au clavier, PHPStan sur les Twig Components.

## Dépendances
- **Dépend de :** US-002, US-003
- **Bloque :** US-005, US-006, US-007

## Definition of Done
Voir `project-management/definition-of-done.md` (fidélité TailAdmin, dark mode, responsive, a11y WCAG AA, PHPStan max, tests ≥80%, Stimulus, séparation bundle/démo, doc).
