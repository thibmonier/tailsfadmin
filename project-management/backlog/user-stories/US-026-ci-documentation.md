# US-026 — CI, documentation et livrabilité du bundle

**EPIC :** EPIC-007-qualite-a11y-doc · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-003 — Mainteneur du bundle**, je veux **disposer d'un pipeline CI complet (PHPStan max, tests ≥ 80 %, lint PHP et front), d'une documentation d'installation et d'usage des composants, d'un CHANGELOG au format Keep a Changelog et d'un versionnement SemVer**, afin de **garantir que tailsfadmin est un bundle professionnel, fiable et adoptable par la communauté Symfony**.

## Conversation
Cette US est la US de « livrabilité » : elle garantit qu'un développeur tiers peut installer le bundle, comprendre son usage et avoir confiance dans sa stabilité. La CI (GitHub Actions ou équivalent) doit inclure les jobs suivants : `php-cs-fixer` + `php-lint` (syntaxe PHP 8.5), `phpstan --level max` sur le répertoire `bundle/src/`, `phpunit` ou `pest` avec rapport de couverture (seuil ≥ 80 % via `Xdebug` ou `PCOV`), `npm run lint` ou `biome check` sur les assets du bundle, et (en option avancée) `axe-core` CLI sur les pages de démo (résultat de US-025). La documentation se compose de : un `README.md` à la racine du bundle expliquant l'installation via Composer (`composer require tailsfadmin/tailsfadmin-bundle`), la configuration minimale (`config/bundles.php`, `importmap.php`, `tailwind.config.js`), et le catalogue de composants listant chaque `<twig:Tailsfadmin:ComponentName />` avec ses paramètres et un exemple d'usage (fichier `docs/components.md` ou pages `demo/templates/docs/`). Le CHANGELOG (`CHANGELOG.md`) suit la spécification [Keep a Changelog](https://keepachangelog.com/) avec les sections `Added`, `Changed`, `Fixed`, `Security` et un en-tête de version SemVer (`## [1.0.0] — YYYY-MM-DD`). Le versionnement du bundle respecte SemVer 2.0 : MAJOR pour breaking changes, MINOR pour nouvelles fonctionnalités rétrocompatibles, PATCH pour corrections. Le fichier `composer.json` du bundle doit exposer les métadonnées requises (`name`, `type: symfony-bundle`, `require`, `autoload`, `extra.symfony.require`). La CI doit bloquer le merge si l'un des jobs échoue.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Pipeline CI complet et livrabilité du bundle
  Scenario: La CI passe sur la branche main sans erreur
    Given un commit est poussé sur la branche "main" du dépôt
    When le pipeline CI s'exécute
    Then le job "PHPStan max" réussit avec 0 erreur sur "bundle/src/"
    And le job "Tests" réussit avec une couverture ≥ 80 %
    And le job "Lint PHP" réussit sans erreur de syntaxe ou de style
    And le job "Lint Front" réussit sans erreur sur les assets du bundle
    And la CI marque le commit "success" (vert)
```
### Scénarios alternatifs
```gherkin
  Scenario: Installation du bundle dans un projet Symfony vierge via la documentation
    Given un développeur suit le README.md du bundle pas à pas
    When il exécute "composer require tailsfadmin/tailsfadmin-bundle" puis configure "importmap.php"
    Then le bundle est fonctionnel et la page "/" du projet affiche le layout tailsfadmin sans erreur

  Scenario: Accès au catalogue de composants dans la documentation
    Given le développeur consulte "docs/components.md" ou la page "/docs/components" de la démo
    When il recherche le composant "Alert"
    Then il trouve la balise Twig "<twig:Tailsfadmin:Alert type='success' message='...' />"
    And un exemple rendu HTML du composant est affiché
    And les paramètres acceptés (type, message, dismissible) sont listés avec leur type et valeur par défaut

  Scenario: Création d'une nouvelle version SemVer MINOR
    Given un tag "v1.1.0" est créé sur le dépôt
    When la CI s'exécute sur ce tag
    Then le job "CHANGELOG check" vérifie que "## [1.1.0]" existe dans CHANGELOG.md
    And le numéro de version dans "composer.json" correspond au tag

  Scenario: Un job CI échoue suite à une régression PHPStan
    Given un développeur introduit une erreur de typage dans "bundle/src/Twig/Component/Alert.php"
    When il pousse un commit sur une branche de feature
    Then le job "PHPStan max" échoue et affiche l'erreur de typage avec le fichier et la ligne concernés
    And le merge vers "main" est bloqué par la règle de branche protégée
```
### Scénarios d'erreur
```gherkin
  Scenario: Couverture de tests inférieure au seuil
    Given la suite de tests couvre seulement 65 % des lignes du bundle
    When le job "Tests" s'exécute avec le seuil configuré à 80 %
    Then le job échoue avec le message "Coverage 65% is below the required 80%"
    And le rapport HTML de couverture est archivé comme artefact CI pour consultation

  Scenario: Fichier CHANGELOG.md absent ou section de version manquante
    Given le fichier CHANGELOG.md ne contient pas de section pour la version du tag créé
    When la CI valide le CHANGELOG
    Then le job "CHANGELOG check" échoue avec le message "Version X.Y.Z not found in CHANGELOG.md"
    And le message indique le format attendu selon Keep a Changelog
```

## INVEST
- **Independent :** Peut être initiée dès US-001 (squelette) ; ne dépend pas du contenu des composants individuels pour le pipeline, uniquement du squelette du bundle.
- **Negotiable :** Le nombre de jobs CI et l'outil de lint front sont négociables (biome vs eslint vs stylelint) ; le seuil de couverture 80 % est la limite basse non négociable.
- **Valuable :** Sans CI ni documentation, le bundle ne peut pas être adopté en production ; c'est le ticket qui transforme le projet en produit.
- **Estimable :** Configuration GitHub Actions, intégration PHPStan/tests/lint, rédaction README + catalogue composants + CHANGELOG ; 8 points reflètent l'effort de documentation substantiel.
- **Small :** Périmètre bien défini : pipeline + doc + CHANGELOG ; pas de nouveau composant UI.
- **Testable :** La CI elle-même est le test ; la documentation est vérifiable par le scénario d'installation pas à pas.

## Dépendances
- **Dépend de :** US-001 (squelette bundle — structure Composer et autoload requis)
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.
