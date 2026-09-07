# US-001 — Squelette bundle Symfony réutilisable + application de démo

**EPIC :** EPIC-001-fondations-socle-technique · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** 1

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur** et **P-003 — Mainteneur du bundle**, je veux **disposer d'un dépôt structuré distinguant le bundle Symfony réutilisable de l'application de démo qui le consomme**, afin de **pouvoir développer, tester et livrer tailsfadmin comme un package installable par n'importe quel projet Symfony**.

## Conversation
Le projet tailsfadmin adopte une architecture monorepo : un répertoire `bundle/` contenant le bundle Symfony (namespace `Tailsfadmin\TailsfadminBundle`) qui expose `config/`, `src/`, `templates/` et `assets/` ; et un répertoire `demo/` contenant l'application Symfony de démo qui déclare le bundle via `config/bundles.php` et le require via le `composer.json` local (path repository). Cette séparation est la décision d'architecture fondatrice : tout code réutilisable va dans le bundle, tout code d'usage illustratif reste dans la démo. La structure doit prévoir `TailsfadminBundle::class`, le `DependencyInjection/TailsfadminExtension.php` et un `Resources/config/services.yaml` minimal. La démo expose un `HomeController` retournant un template vide héritant du futur layout. L'intégration Symfony UX (Twig Components, Stimulus) sera enregistrée dans le bundle. Aucun code fonctionnel de thème n'est attendu à ce stade : le livrable est la charpente démarrable confirmée par une route `/` renvoyant HTTP 200.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Squelette bundle + démo démarrable
  Scenario: La démo démarre et répond HTTP 200
    Given le dépôt est cloné et les dépendances installées via "composer install"
    When le développeur lance l'application de démo (serveur Symfony ou FrankenPHP)
    Then la route "/" répond HTTP 200
    And la réponse HTML contient la chaîne "tailsfadmin"
    And aucune erreur Symfony n'est loguée dans "var/log/dev.log"
```
### Scénarios alternatifs
```gherkin
  Scenario: Le bundle est reconnu par Symfony Kernel
    Given la démo est démarrée
    When le développeur exécute "php bin/console debug:container tailsfadmin"
    Then la sortie liste au moins un service préfixé "tailsfadmin."

  Scenario: La démo peut être installée dans un projet Symfony tiers via path repository
    Given un projet Symfony vierge avec le dépôt tailsfadmin disponible en path repository
    When le développeur ajoute la dépendance et active le bundle dans "config/bundles.php"
    Then "composer install" réussit sans erreur
    And le bundle est reconnu par le kernel du projet tiers
```
### Scénarios d'erreur
```gherkin
  Scenario: Détection d'une dépendance circulaire bundle/démo
    Given le bundle référence une classe du namespace de la démo
    When le développeur exécute "php bin/console cache:warmup"
    Then la commande échoue avec un message indiquant la dépendance interdite
    And un commentaire dans le code source précise la règle d'isolation

  Scenario: Absence du fichier de configuration du bundle
    Given le fichier "bundle/config/services.yaml" est supprimé
    When le développeur exécute "php bin/console cache:warmup" dans la démo
    Then Symfony lève une exception "FileLocatorFileNotFoundException" explicite
    And le message indique le chemin attendu du fichier manquant
```

## INVEST
- **Independent :** Aucune US existante ne préexiste ; cette US est le point zéro du projet.
- **Negotiable :** La structure exacte des répertoires (monorepo vs dépôts séparés) est discutable en début de sprint ; l'option monorepo est recommandée mais peut être révisée.
- **Valuable :** Sans charpente, aucune autre US ne peut être développée ni testée.
- **Estimable :** Structure connue (Symfony MakerBundle, convention bundle) ; 5 points reflètent la mise en place initiale et la validation CI minimale.
- **Small :** Limitée à la structure et au démarrage ; aucune fonctionnalité UI n'est incluse.
- **Testable :** Critères objectifs : HTTP 200, service listé, warmup sans erreur.

## Dépendances
- **Dépend de :** —
- **Bloque :** US-002, US-003, US-004, US-005, US-006, US-007 (et quasiment toutes les US suivantes)

## Definition of Done
Voir `project-management/definition-of-done.md` (fidélité TailAdmin, dark mode, responsive, a11y WCAG AA, PHPStan max, tests ≥80%, Stimulus, séparation bundle/démo, doc).
