# US-034 — Auth & utilitaires étendus (reset password, 2FA/OTP, 500, maintenance, coming-soon, success)

**EPIC :** EPIC-009-pages-exemples · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Could · **Sprint :** Sprint 9-10

## Carte (Card)
> En tant que **P-004 / P-001**, je veux **des écrans d'authentification et utilitaires supplémentaires (mot de passe oublié/réinitialisation, 2FA/OTP, erreurs 500, maintenance, coming-soon, succès)**, afin de **couvrir les parcours secondaires courants d'une application**.

## Conversation
Extension d'US-023 : réutilise le layout auth centré et les composants form.
Écrans : **reset password** (demande + nouveau mot de passe), **2FA/OTP** (saisie de
code segmentée), **500** (erreur serveur, sans stack en prod), **maintenance**,
**coming-soon** (avec éventuel compte à rebours en Stimulus), **success**
(confirmation). UI de démo (pas de logique d'auth réelle). Le composant de saisie
OTP (champs segmentés) peut justifier un petit contrôleur Stimulus si réutilisable.
Clair+dark, accessible, responsive.

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Écrans auth & utilitaires étendus
  Scenario: Rendu et accessibilité
    Given l'utilisateur ouvre l'un des écrans (reset|otp|500|maintenance|coming-soon|success)
    Then la page retourne 200 (ou le bon code pour les pages d'erreur en prod)
    And le rendu est correct clair+dark, 0 violation axe A/AA

  Scenario: 500 en production
    Given l'environnement de production
    When une erreur serveur survient
    Then la page 500 métier s'affiche sans exposer de stack trace

  Scenario: Saisie OTP au clavier
    Given l'écran 2FA/OTP
    When l'utilisateur tape un code
    Then le focus avance automatiquement entre les champs et recule sur effacement
```

## INVEST
- **Independent** : écrans autonomes, extension d'US-023.
- **Negotiable** : sous-ensemble selon capacité (Could).
- **Valuable** : complète les parcours secondaires.
- **Estimable** : ~6 écrans + petit contrôleur OTP — 5 pts.
- **Small** : pages simples réutilisant l'existant.
- **Testable** : tests fonctionnels + E2E (OTP) + axe.

## Dépendances
- **Dépend de :** US-023 (layout auth + form), EPIC-008.
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.
