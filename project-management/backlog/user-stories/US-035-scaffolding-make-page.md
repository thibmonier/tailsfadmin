# US-035 — Scaffolding : commande `make:tailsfadmin-page`

**EPIC :** EPIC-009-pages-exemples · **Statut :** 🟢 Done · **Points :** 5 · **Priorité :** Could · **Sprint :** Sprint 10

## Carte (Card)
> En tant que **P-003 — Développeur intégrant le thème**, je veux **une commande qui génère une nouvelle page conforme au thème (route + template étendant le layout admin, à partir d'un gabarit : vierge, dashboard, table, formulaire)**, afin de **démarrer une page en secondes sans copier-coller**.

## Conversation
Fournir un maker (via `symfony/maker-bundle` en require-dev de l'app, ou une commande
console du bundle) `make:tailsfadmin-page` qui, selon un **gabarit** choisi (blank,
dashboard, table, form), génère : un contrôleur (ou route), un template étendant
`@Tailsfadmin/layout/admin.html.twig` avec des composants tsf pré-câblés, et
éventuellement une entrée de menu. Non-interactif possible (arguments up-front, cf.
AGENTS.md). Le code généré doit passer PHPStan/cs-fixer et respecter l'a11y.

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Génération de page depuis un gabarit
  Scenario: Générer une page dashboard
    Given une app avec le bundle installé
    When le développeur exécute make:tailsfadmin-page --template=dashboard --name=Sales
    Then un contrôleur et un template sont créés, étendant le layout admin
    And la page retourne 200 et assemble des composants tsf
    And le code généré passe PHPStan max et php-cs-fixer

  Scenario: Gabarit inconnu
    Given un gabarit non supporté
    When la commande est exécutée
    Then une erreur claire liste les gabarits disponibles
```

## INVEST
- **Independent** : outil de confort, s'appuie sur les pages existantes comme gabarits.
- **Negotiable** : liste des gabarits, maker vs commande bundle.
- **Valuable** : accélère l'adoption et la cohérence des pages.
- **Estimable** : maker + 3-4 gabarits + test — 5 pts.
- **Small** : un générateur, périmètre borné.
- **Testable** : test de génération (fichiers créés + page 200 + qualité).

## Dépendances
- **Dépend de :** US-032/US-033 (gabarits issus des pages), EPIC-008.
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.
