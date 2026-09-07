# US-025 — Audit et mise en conformité accessibilité (WCAG AA transversal)

**EPIC :** EPIC-007-qualite-a11y-doc · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-004 — Utilisateur final de l'interface admin**, je veux **naviguer dans l'ensemble des pages de la démo (dashboard, profil, authentification, utilitaires) exclusivement au clavier et avec un lecteur d'écran**, afin de **utiliser l'interface sans dépendre de la souris et garantir que tailsfadmin est inclusif pour les utilisateurs en situation de handicap**.

## Conversation
Cette US couvre l'audit d'accessibilité transversal de l'application de démo une fois toutes les pages assemblées (US-021, US-022, US-023). Les points de contrôle WCAG 2.2 niveau AA prioritaires pour un thème admin sont : navigation clavier complète (Tab, Shift+Tab, Entrée, Échap, flèches sur les menus) ; indicateur de focus visible sur chaque élément interactif (outline non supprimé, ratio de contraste ≥ 3:1 sur le focus ring) ; attributs ARIA sur les composants interactifs (menus déroulants `role="menu"` + `aria-expanded`, modals `role="dialog"` + `aria-modal="true"` + `aria-labelledby`, onglets `role="tablist"`/`role="tab"`, barres de progression `role="progressbar"` + `aria-valuenow`) ; contrastes de couleur ≥ 4,5:1 pour le texte normal et ≥ 3:1 pour le texte large, vérifiés en mode clair ET sombre ; attributs `alt` sur toutes les images non décoratives ; liens avec libellés descriptifs (pas de "cliquez ici") ; ordre de tabulation logique correspondant à l'ordre visuel. L'outil d'audit de référence est `axe-core` (intégrable dans les tests Panther via `axe-php/webdriver` ou CLI `axe`). Les corrections sont apportées directement dans les templates du bundle. La priorité MUST signifie qu'aucune issue WCAG AA ne doit subsister dans les pages de démo au moment de la livraison du bundle. Le mode RTL (US-024) doit également être audité pour l'ordre de lecture.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Navigation clavier complète sur le dashboard
  Scenario: Parcours clavier du tableau de bord sans utilisation de la souris
    Given l'utilisateur est sur la page "/" de la démo
    And aucune souris n'est utilisée
    When l'utilisateur presse Tab successivement
    Then chaque élément interactif (liens de navigation, boutons, champs) reçoit le focus dans un ordre logique
    And chaque élément focusé est visuellement mis en évidence par un outline visible
    And l'utilisateur peut activer chaque élément focusé avec la touche Entrée ou Espace selon le type
```
### Scénarios alternatifs
```gherkin
  Scenario: Fermeture d'une modal via la touche Échap
    Given la modal d'édition du profil est ouverte (US-022)
    When l'utilisateur presse la touche Échap
    Then la modal se ferme
    And le focus retourne sur l'élément déclencheur (bouton "Modifier")
    And aucun élément de la modal n'est focusable après sa fermeture (inert ou display:none)

  Scenario: Vérification des contrastes en mode sombre
    Given la classe "dark" est active sur <html>
    When un audit axe-core est lancé sur la page dashboard
    Then aucune violation de contraste WCAG AA n'est reportée pour le texte normal (≥ 4,5:1)
    And aucune violation n'est reportée pour les composants graphiques non textuels (≥ 3:1)

  Scenario: Attributs ARIA sur le menu de navigation latéral
    Given la sidebar est affichée et un sous-menu est replié
    When le développeur inspecte le DOM
    Then le bouton de déploiement du sous-menu porte l'attribut "aria-expanded='false'"
    And après déploiement l'attribut passe à "aria-expanded='true'"
    And les éléments du sous-menu replié ont l'attribut "aria-hidden='true'"
```
### Scénarios d'erreur
```gherkin
  Scenario: Détection d'une violation WCAG AA bloquante par axe-core
    Given un audit axe-core automatisé est lancé en CI sur la page dashboard
    When une violation de type "color-contrast" ou "label" est détectée
    Then le test CI échoue avec un rapport listant la violation, l'élément HTML concerné et la règle WCAG violée
    And le pipeline bloque le merge jusqu'à correction

  Scenario: Image non décorative sans attribut alt
    Given une image illustrative est présente dans la page profil (US-022)
    When un audit axe-core est lancé
    Then une violation "image-alt" est signalée si l'attribut alt est absent ou vide
    And la correction attendue (attribut alt descriptif) est indiquée dans le rapport
```

## INVEST
- **Independent :** L'audit est réalisable dès que les pages sont assemblées ; ne modifie pas l'architecture des composants.
- **Negotiable :** Le périmètre peut être élargi au niveau AAA sur certains critères spécifiques si le temps le permet, mais AA est le minimum.
- **Valuable :** L'accessibilité est un critère légal dans de nombreux pays (EN 301 549, RGAA) ; son absence peut bloquer l'adoption du bundle dans des contextes publics ou institutionnels.
- **Estimable :** Audit axe-core, corrections ARIA/focus/contraste, intégration en CI ; 5 points car les pages source existent déjà.
- **Small :** Porte uniquement sur les pages démo livrées ; ne crée pas de nouveaux composants.
- **Testable :** Axe-core en CI, tests Panther de navigation clavier, rapport de violations — résultats binaires et automatisables.

## Dépendances
- **Dépend de :** US-021 (dashboard assemblé), US-022 (profil assemblé), US-023 (pages auth/utilitaires)
- **Bloque :** US-026 (CI doit intégrer les tests a11y)

## Definition of Done
Voir `project-management/definition-of-done.md`.
