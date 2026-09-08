# US-030 — Test d'intégration dans une app Symfony vierge (CI)

**EPIC :** EPIC-008-distribution-consommabilite · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** Sprint 8

## Carte (Card)
> En tant que **P-003 — Mainteneur**, je veux **un job CI qui crée une app Symfony neuve, y installe le bundle (comme un tiers), et vérifie qu'une page admin s'affiche et fonctionne**, afin de **garantir que la consommabilité (US-027/028/029) marche réellement hors du monorepo de la démo**.

## Conversation
La démo actuelle consomme le bundle en **path repository** (couplée) : elle ne prouve
pas l'expérience d'un tiers. Ce test crée un projet Symfony minimal (skeleton +
AssetMapper), installe le bundle via le canal cible (Packagist une fois publié, sinon
VCS/path pour préparer), applique les étapes documentées (assets US-027, CSS US-028,
recette US-029), et lance un smoke test (page qui étend le layout admin → HTTP 200 +
sidebar/header rendus + un composant JS monté). Exécuté en CI (matrice de versions
Symfony si pertinent).

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Intégration prouvée sur app vierge
  Scenario: Smoke test d'une app tierce
    Given un projet Symfony neuf créé en CI
    And le bundle installé et configuré selon la documentation
    When une page étend @Tailsfadmin/layout/admin.html.twig
    Then la requête HTTP retourne 200 avec sidebar + header rendus
    And le CSS du thème est appliqué (classes générées)
    And un composant JS (ex. dropdown/modal) fonctionne au navigateur

  Scenario: Échec si une étape d'assets manque
    Given l'app n'a pas exécuté l'étape de vendoring des libs (US-027)
    When une page charge un tsf:Chart:*
    Then le test échoue avec un message pointant l'étape manquante
```

## INVEST
- **Independent** : test transversal validant les autres US de l'EPIC.
- **Negotiable** : périmètre du smoke test (1 page vs plusieurs) négociable.
- **Valuable** : seul moyen fiable de prouver l'expérience d'adoption tierce.
- **Estimable** : script création app + install + smoke + job CI — 5 pts.
- **Small** : un smoke test, pas une suite complète.
- **Testable** : la CI EST le test (binaire vert/rouge).

## Dépendances
- **Dépend de :** US-027, US-028 (US-029 si recette prête).
- **Bloque :** US-031 (publier après une intégration prouvée).

## Definition of Done
Voir `project-management/definition-of-done.md`.
