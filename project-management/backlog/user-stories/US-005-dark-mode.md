# US-005 — Bascule clair/dark persistante

**EPIC :** EPIC-002-layout-navigation · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** 1

## Carte (Card)
> En tant que **P-002 — Designer front-end** et **P-004 — Utilisateur administrateur**, je veux **une bascule clair/sombre persistante et cohérente sur tous les composants du thème**, afin de **adapter l'interface à mes préférences visuelles sans que le choix soit perdu à chaque rechargement de page**.

## Conversation
La source de référence est le composant `components/common/theme-toggle` de `Tools/sources/tailadmin-laravel-main/` qui utilise Alpine.js avec `x-data` et `Alpine.store('darkMode')` (persist plugin). Cette logique Alpine est entièrement convertie en un contrôleur Stimulus `theme-toggle_controller.js` exposé par le bundle. Le contrôleur : (1) lit `localStorage.getItem('theme')` à l'initialisation, (2) applique ou retire la classe `.dark` sur `<html>`, (3) écoute le clic sur le bouton pour basculer et persister, (4) respecte `prefers-color-scheme` si aucune préférence stockée n'est trouvée. Le bouton toggle est rendu dans le header (US-004/US-007) avec les icônes soleil/lune en SVG inline issues de `tailadmin-free-tailwind-dashboard-template-main/src/partials/header.html`. La stratégie dark mode repose sur la classe `.dark` sur `<html>` (non sur `prefers-color-scheme` seul) pour être cohérente avec les tokens CSS définis en US-002. Point critique : éviter le flash of unstyled content (FOUC) en injectant un script inline bloquant dans `<head>` qui applique `.dark` avant le premier paint. Ce script est minimal (< 200 octets) et ne dépend pas de Stimulus.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Bascule dark mode persistante via Stimulus
  Scenario: L'utilisateur bascule en mode sombre et recharge la page
    Given la page "/" est chargée en mode clair
    And aucune préférence n'est stockée dans localStorage
    When l'utilisateur clique sur le bouton "theme-toggle"
    Then la classe ".dark" est ajoutée à l'élément "<html>"
    And le fond de page passe aux couleurs sombres du thème TailAdmin
    And localStorage contient la valeur "dark" pour la clé "theme"
    When l'utilisateur recharge la page
    Then la classe ".dark" est présente sur "<html>" dès le premier paint
    And aucun flash de couleur claire n'est perceptible
```
### Scénarios alternatifs
```gherkin
  Scenario: Respect de la préférence système à la première visite
    Given aucune préférence n'est stockée dans localStorage
    And le système d'exploitation est configuré en mode sombre
    When le navigateur charge la page "/"
    Then la classe ".dark" est appliquée sur "<html>"
    And le thème sombre est affiché sans interaction utilisateur

  Scenario: Retour au mode clair depuis le mode sombre
    Given localStorage contient la valeur "dark" pour la clé "theme"
    And la page est chargée en mode sombre
    When l'utilisateur clique sur le bouton "theme-toggle"
    Then la classe ".dark" est retirée de "<html>"
    And localStorage contient la valeur "light" pour la clé "theme"
    And tous les composants affichés reprennent les couleurs claires
```
### Scénarios d'erreur
```gherkin
  Scenario: localStorage indisponible (navigation privée bloquée)
    Given le navigateur bloque l'accès à localStorage
    When la page "/" est chargée
    Then le thème clair est affiché par défaut sans erreur JavaScript
    And la console ne contient aucune exception non gérée liée au storage

  Scenario: Le contrôleur Stimulus "theme-toggle" n'est pas enregistré
    Given le fichier "theme-toggle_controller.js" n'est pas importé dans l'importmap
    When l'utilisateur clique sur le bouton toggle
    Then aucun changement de thème ne se produit
    And la console navigateur affiche un avertissement Stimulus "controller not found: theme-toggle"
```

## INVEST
- **Independent :** Fonctionnalité autonome ; seul le bouton toggle dans le layout (US-004) est requis.
- **Negotiable :** L'icône animée (transition soleil↔lune) est négociable pour un sprint ultérieur ; le comportement fonctionnel est non négociable.
- **Valuable :** Le dark mode est une fonctionnalité attendue de tout thème admin moderne et demandée explicitement dans le périmètre TailAdmin.
- **Estimable :** 5 points ; logique Stimulus simple mais FOUC à traiter soigneusement.
- **Small :** Périmètre limité à la bascule et la persistance ; le style des composants individuels en dark est couvert par les tokens (US-002).
- **Testable :** Classe sur `<html>` vérifiable en DOM, localStorage lisible, FOUC mesurable avec Lighthouse.

## Dépendances
- **Dépend de :** US-004
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md` (fidélité TailAdmin, dark mode, responsive, a11y WCAG AA, PHPStan max, tests ≥80%, Stimulus, séparation bundle/démo, doc).
