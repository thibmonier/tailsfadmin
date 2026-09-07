# US-023 — Pages authentification et utilitaires (signin, signup, blank, 404)

**EPIC :** EPIC-006-pages-i18n · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Should · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur**, je veux **disposer dans la démo des pages d'authentification (connexion, inscription) et des pages utilitaires (gabarit vide, erreur 404) stylisées avec le bundle**, afin de **couvrir les cas d'usage classiques d'un thème admin et valider que le bundle fonctionne hors du layout principal**.

## Conversation
TailAdmin fournit quatre pages complémentaires au dashboard : `src/signin.html` (formulaire email + password + remember me, lien "Mot de passe oublié"), `src/signup.html` (formulaire inscription complet avec champs nom, email, password), `src/blank.html` (gabarit minimal avec layout principal pour servir de point de départ à l'intégrateur), et `src/404.html` (page d'erreur personnalisée avec illustration et lien de retour). Ces pages sont dans l'app de démo, pas dans le bundle. Les formulaires sign-in/sign-up utilisent les composants Form du bundle (US-014) et les boutons (US-010) ; ils ne sont pas reliés à un système d'authentification réel (démo statique). La page `blank.html` hérite du layout principal du bundle (US-004) et représente le gabarit recommandé pour débuter une page personnalisée. La page 404 utilise un layout alternatif (sans sidebar) ; son template Twig doit être enregistré dans le bundle comme template d'erreur Symfony (`templates/bundles/TwigBundle/Exception/error404.html.twig`). Les pages signin/signup utilisent un layout centré sans sidebar ni header (layout `auth` distinct du layout principal). Le dark mode, le responsive (formulaire centré sur mobile, illustration latérale sur desktop) et la navigation clavier sur les formulaires sont obligatoires. Aucune logique serveur d'authentification n'est dans le scope.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Pages authentification et utilitaires
  Scenario: Affichage de la page de connexion (signin)
    Given l'utilisateur navigue vers la route "/signin" de la démo
    When la page se charge
    Then la page affiche un formulaire avec les champs "Email" et "Mot de passe"
    And un bouton "Se connecter" est visible et activable au clavier
    And un lien "Mot de passe oublié ?" est présent
    And la page n'affiche ni sidebar ni header de navigation principal
```
### Scénarios alternatifs
```gherkin
  Scenario: Affichage de la page d'inscription (signup)
    Given l'utilisateur navigue vers la route "/signup" de la démo
    When la page se charge
    Then la page affiche les champs Prénom, Nom, Email, Mot de passe, Confirmation du mot de passe
    And un bouton "S'inscrire" est présent
    And un lien "Déjà inscrit ? Se connecter" est présent

  Scenario: Utilisation de la page blank comme gabarit de départ
    Given un développeur intégrateur copie le template "blank.html.twig" de la démo
    When il ajoute son propre contenu dans le bloc Twig "content"
    Then le layout principal (sidebar, header, footer) est rendu automatiquement autour du contenu
    And aucun CSS supplémentaire n'est nécessaire pour l'intégration de base

  Scenario: Affichage de la page 404 en mode dark
    Given la classe "dark" est active sur <html>
    When l'utilisateur atteint une URL inexistante de la démo
    Then Symfony rend le template d'erreur 404 personnalisé du bundle
    And la page affiche l'illustration, un titre "Page non trouvée" et un bouton "Retour à l'accueil" en couleurs dark
```
### Scénarios d'erreur
```gherkin
  Scenario: Soumission du formulaire signin avec champs vides
    Given l'utilisateur est sur la page "/signin"
    When il clique sur "Se connecter" sans remplir Email ni Mot de passe
    Then les deux champs affichent un message d'erreur de validation HTML5 natif ou via composant Form
    And le formulaire n'est pas soumis

  Scenario: Accès à une URL inexistante de la démo en production
    Given la démo est exécutée en mode "prod" (APP_ENV=prod)
    When une requête est faite vers "/cette-page-nexiste-pas"
    Then Symfony retourne HTTP 404 et rend le template d'erreur personnalisé du bundle
    And aucune stack trace n'est exposée dans la réponse HTML
```

## INVEST
- **Independent :** Aucune dépendance vers un système auth réel ; les pages sont des démos statiques stylisées.
- **Negotiable :** La page blank peut être simplifiée ou étoffée ; la 404 peut utiliser une illustration différente.
- **Valuable :** Complète le catalogue de pages de la démo ; un intégrateur s'attend à trouver ces pages dans tout thème admin.
- **Estimable :** 4 templates Twig, 2 layouts (auth + main), composants déjà fournis par le bundle ; estimé 5 points.
- **Small :** Limité à 4 pages, pas de logique métier, pas de nouveau composant bundle.
- **Testable :** Routes HTTP, présence des éléments de formulaire, rendu 404, dark mode — tous vérifiables automatiquement.

## Dépendances
- **Dépend de :** US-004 (layout principal), US-010 (boutons), US-014 (composants formulaire)
- **Bloque :** US-025 (audit a11y — pages assemblées requises)

## Definition of Done
Voir `project-management/definition-of-done.md`.
