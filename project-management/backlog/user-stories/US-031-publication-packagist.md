# US-031 — Publication Packagist (canal de distribution)

**EPIC :** EPIC-008-distribution-consommabilite · **Statut :** 🔴 To Do · **Points :** 3 · **Priorité :** Must · **Sprint :** Sprint 8

## Carte (Card)
> En tant que **P-003 — Mainteneur**, je veux **publier le bundle sur Packagist.org (public)**, afin qu'**un `composer require tailsfadmin/tailsfadmin-bundle` fonctionne dans hottwos (et tout projet tiers) sans déclarer de dépôt VCS**.

## Conversation
Décision « Packagist d'abord » : Packagist est le canal retenu. Publication réalisée
**une fois la consommabilité verte** (US-027..030) pour éviter une première version
publique cassée. Étapes : soumettre le dépôt à Packagist (webhook auto-update des
tags), **retirer le champ `version` de `composer.json`** (les tags git font foi),
vérifier les métadonnées (name, type `symfony-bundle`, license, keywords,
`extra.branch-alias`), ajouter des badges (version, licence, CI) au README, et
publier une version (tag). Envisager la contribution de la recette Flex (US-029) à
`symfony/recipes-contrib` après publication.

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Bundle disponible sur Packagist
  Scenario: Require standard
    Given le package est publié sur Packagist
    When un développeur exécute "composer require tailsfadmin/tailsfadmin-bundle:^1"
    Then l'installation réussit sans repository VCS déclaré
    And la version installée correspond au dernier tag stable

  Scenario: Mise à jour automatique sur nouveau tag
    Given un tag v1.1.0 est poussé
    When le webhook Packagist se déclenche
    Then la version 1.1.0 apparaît sur Packagist

  Scenario: Métadonnées propres
    Given la page Packagist du package
    Then le champ version n'est plus figé dans composer.json (tags git)
    And le type est symfony-bundle, la licence MIT, les mots-clés présents
```

## INVEST
- **Independent** : dernière étape de distribution ; s'appuie sur les précédentes.
- **Negotiable** : moment de publication (après US-030) et badges.
- **Valuable** : rend `composer require` standard — objectif retenu pour hottwos.
- **Estimable** : soumission + webhook + nettoyage métadonnées + badges — 3 pts.
- **Small** : acte de publication, pas de code produit.
- **Testable** : `composer require` sur une app vierge sans repo déclaré (lié à US-030).

## Dépendances
- **Dépend de :** US-027, US-028, US-030 (intégration prouvée) ; US-029 (recette, si contrib).
- **Bloque :** intégration Packagist-standard dans hottwos.

## Definition of Done
Voir `project-management/definition-of-done.md`.
