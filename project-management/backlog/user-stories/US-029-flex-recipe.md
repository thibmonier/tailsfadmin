# US-029 — Flex recipe (configuration automatique)

**EPIC :** EPIC-008-distribution-consommabilite · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Should · **Sprint :** Sprint 8

## Carte (Card)
> En tant que **P-003 — Consommateur du bundle**, je veux **qu'à l'installation, Symfony Flex configure automatiquement le bundle (enregistrement dans `bundles.php`, `config/packages/tailsfadmin.yaml` par défaut, rappel des étapes assets)**, afin de **démarrer sans configuration manuelle**.

## Conversation
Une recette Flex fournit : l'enregistrement du bundle, un `tailsfadmin.yaml` de base
(menu minimal + locales), et éventuellement un post-install-message rappelant les
commandes d'assets (US-027/028). Deux voies : (a) contribution à
`symfony/recipes-contrib` (public, nécessite Packagist + revue), (b) recette privée
via un `endpoint` Flex du dépôt. Comme « Packagist d'abord » est retenu, viser la
contrib publique (ou un dépôt de recettes dédié). La recette ne doit rien casser si
l'app n'utilise pas AssetMapper (dégradation documentée).

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Configuration automatique via Flex
  Scenario: Installation avec recette
    Given une app Symfony avec Flex
    When le développeur exécute composer require du bundle
    Then le bundle est enregistré dans config/bundles.php
    And un config/packages/tailsfadmin.yaml par défaut est créé
    And un message post-install rappelle les commandes d'assets

  Scenario: Idempotence
    Given le bundle est déjà installé et configuré
    When la recette est réappliquée
    Then aucune configuration existante n'est écrasée sans confirmation
```

## INVEST
- **Independent** : couche de confort par-dessus US-027/028.
- **Negotiable** : recette contrib publique vs endpoint privé.
- **Valuable** : réduit la friction d'onboarding à zéro configuration.
- **Estimable** : rédaction manifest recette + test — 5 pts.
- **Small** : périmètre = config auto, pas les assets eux-mêmes.
- **Testable** : install sur app vierge (US-030) vérifie bundles.php + yaml générés.

## Dépendances
- **Dépend de :** US-027, US-028.
- **Bloque :** US-031 (Packagist — la recette contrib suppose le package publié).

## Definition of Done
Voir `project-management/definition-of-done.md`.
