# US-006 — Sidebar multi-niveaux, repliable desktop et drawer mobile

**EPIC :** EPIC-002-layout-navigation · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Must · **Sprint :** backlog (Sprint 2)

## Carte (Card)
> En tant que **P-004 — Utilisateur administrateur**, je veux **une sidebar de navigation multi-niveaux qui se replie en mode icônes sur desktop et s'ouvre en drawer avec overlay sur mobile, avec le lien actif mis en évidence**, afin de **naviguer efficacement entre les sections de l'administration quelle que soit la taille de l'écran**.

## Conversation
La sidebar est le composant d'interactivité le plus riche du layout. Source primaire : `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/sidebar.html` (structure HTML complète avec groupes, sous-menus, SVG). Source logique serveur : `Tools/sources/tailadmin-laravel-main/app/Helpers/MenuHelper.php` — méthodes `getMenuGroups()` (groupes de navigation avec items et sous-items) et `isActive()` (comparaison de l'URL courante). La décision d'architecture impose la conversion de TOUTE l'interactivité Alpine (`x-show`, `x-data`, `@click`, `x-transition`) vers un contrôleur Stimulus `sidebar_controller.js` exposé par le bundle avec les targets : `overlay`, `sidebar`, `toggleBtn`, `submenuItem`. États gérés : ouvert/replié (desktop, persisté en localStorage), drawer ouvert/fermé (mobile, overlay activé), état actif de l'item courant (résolu côté serveur via une fonction `is_active()` Twig personnalisée du bundle). Structure RTL : les classes `ltr:` et `rtl:` Tailwind doivent être utilisées dès maintenant sur les marges et positions de la sidebar pour préparer le support arabe (US i18n). Les icônes SVG sont issues directement de `sidebar.html`. Le menu est généré depuis un tableau PHP configurable (`TailsfadminBundle` expose un `MenuBuilder` service) calé sur `MenuHelper::getMenuGroups()`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Sidebar responsive multi-niveaux pilotée par Stimulus
  Scenario: L'utilisateur ouvre un sous-menu en desktop et navigue vers une page
    Given la démo est chargée en viewport desktop (≥1024px)
    And la sidebar est en état "expanded"
    When l'utilisateur clique sur un item parent "UI Elements"
    Then le sous-menu de "UI Elements" s'étend avec une animation de hauteur
    And les autres sous-menus restent fermés
    When l'utilisateur clique sur "Alerts" dans le sous-menu
    Then le navigateur charge la route "/ui/alerts"
    And l'item "Alerts" affiche la classe active "menu-item-active"
    And la sidebar reste en état "expanded"
```
### Scénarios alternatifs
```gherkin
  Scenario: Repliage de la sidebar en desktop (mode icônes)
    Given la sidebar est en état "expanded" sur un viewport desktop
    When l'utilisateur clique sur le bouton de repliage (toggle)
    Then la sidebar passe à une largeur réduite affichant uniquement les icônes
    And les labels texte des items sont masqués
    And localStorage persiste l'état "collapsed"
    When l'utilisateur recharge la page
    Then la sidebar s'initialise en état "collapsed" sans animation au chargement

  Scenario: Ouverture du drawer sur mobile
    Given la démo est chargée en viewport mobile (<768px)
    And la sidebar est masquée (mode drawer fermé)
    When l'utilisateur clique sur le bouton hamburger dans le header
    Then la sidebar s'affiche en overlay depuis la gauche (ou droite en RTL)
    And un fond semi-transparent (overlay) couvre le contenu principal
    And le focus est piégé dans la sidebar ouverte (trap focus)
    When l'utilisateur clique sur l'overlay ou appuie sur Échap
    Then la sidebar se referme et le focus retourne au bouton hamburger
```
### Scénarios d'erreur
```gherkin
  Scenario: Service MenuBuilder non configuré dans le bundle
    Given le fichier "config/packages/tailsfadmin.yaml" est absent de la démo
    When Symfony tente de rendre le template de la sidebar
    Then une exception "MenuBuilder not configured" est levée
    And le message indique le fichier de configuration à créer avec un exemple minimal

  Scenario: Lien actif impossible à déterminer (route inconnue)
    Given la page courante correspond à une route non référencée dans MenuBuilder
    When la sidebar est rendue
    Then aucun item n'est mis en évidence comme actif
    And aucune exception PHP n'est levée
    And le journal Symfony ne contient aucune erreur de niveau ERROR
```

## INVEST
- **Independent :** Dépend de US-004 (layout) mais indépendante du header (US-007).
- **Negotiable :** Les animations de transition (durée, easing) et la persistance de l'état des sous-menus ouverts sont négociables.
- **Valuable :** La sidebar est le principal outil de navigation de l'admin ; sans elle, l'application est inutilisable.
- **Estimable :** 8 points maximum ; conversion Alpine complexe, RTL à anticiper, accessibilité mobile (trap focus) non triviale.
- **Small :** Limitée à la sidebar ; les autres composants de navigation (header, breadcrumb) sont dans d'autres US.
- **Testable :** États DOM vérifiables, localStorage contrôlable, focus trap testable avec outils a11y.

## Dépendances
- **Dépend de :** US-004
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md` (fidélité TailAdmin, dark mode, responsive, a11y WCAG AA, PHPStan max, tests ≥80%, Stimulus, séparation bundle/démo, doc).
