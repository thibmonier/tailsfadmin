# US-003 — Runtime FrankenPHP / PHP 8.5

**EPIC :** EPIC-001-fondations-socle-technique · **Statut :** 🔴 To Do · **Points :** 3 · **Priorité :** Must · **Sprint :** 1

## Carte (Card)
> En tant que **P-003 — Mainteneur du bundle**, je veux **lancer l'application de démo sous FrankenPHP avec PHP 8.5 via Docker Compose**, afin de **garantir un environnement d'exécution reproductible, identique en local et en CI, et conforme aux prérequis minimaux du bundle**.

## Conversation
FrankenPHP est le serveur PHP retenu pour tailsfadmin (PHP 8.5, serveur HTTP intégré, HTTP/2 et HTTP/3 natifs). L'US couvre : la rédaction du `Dockerfile` multi-stage basé sur l'image officielle `dunglas/frankenphp:php8.5`, le fichier `compose.yaml` exposant les ports 80/443, la configuration de la variable `SERVER_NAME`, et la commande `frankenphp php-server` (ou `frankenphp run` avec `Caddyfile`). Le mode worker FrankenPHP (préchargement de l'app Symfony) est évalué mais optionnel à ce stade : si activé, il doit être documenté dans l'ADR correspondant. Le `compose.yaml` définit également les volumes pour `var/` (cache, logs) et `public/` (assets). L'image doit passer PHPStan niveau max sur le code du bundle sans erreur. Un health-check Docker (`curl -f http://localhost/ || exit 1`) confirme la disponibilité. Cette US ne couvre pas la mise en production ni le TLS auto-signé avancé.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Démarrage de la démo sous FrankenPHP via Docker Compose
  Scenario: La démo répond HTTP 200 après "docker compose up"
    Given Docker est installé et le dépôt cloné
    When le développeur exécute "docker compose up --build -d"
    Then le conteneur "tailsfadmin-demo" passe à l'état "healthy"
    And la requête "curl -s -o /dev/null -w '%{http_code}' http://localhost/" retourne "200"
    And les logs du conteneur ne contiennent aucune erreur PHP fatale
```
### Scénarios alternatifs
```gherkin
  Scenario: La version PHP du conteneur est bien 8.5
    Given le conteneur "tailsfadmin-demo" est démarré
    When le développeur exécute "docker compose exec demo php -v"
    Then la sortie contient "PHP 8.5"
    And FrankenPHP est mentionné dans la bannière PHP

  Scenario: Le cache Symfony se reconstruit correctement dans le conteneur
    Given le conteneur est démarré
    When le développeur exécute "docker compose exec demo php bin/console cache:warmup"
    Then la commande se termine avec le code de sortie 0
    And le répertoire "var/cache/dev/" contient les fichiers compilés
```
### Scénarios d'erreur
```gherkin
  Scenario: Port 80 déjà utilisé sur la machine hôte
    Given un autre service écoute sur le port 80 de la machine hôte
    When le développeur exécute "docker compose up"
    Then Docker retourne une erreur "port is already allocated"
    And le fichier "compose.yaml" documente en commentaire comment changer le port hôte

  Scenario: Image FrankenPHP indisponible (mode hors-ligne)
    Given Docker n'a pas accès au registre Docker Hub
    And l'image "dunglas/frankenphp:php8.5" n'est pas dans le cache local
    When le développeur exécute "docker compose up --build"
    Then Docker retourne une erreur réseau explicite
    And le README indique la commande de pré-pull à exécuter en amont
```

## INVEST
- **Independent :** L'infrastructure Docker est indépendante des choix CSS et de l'UI.
- **Negotiable :** Le mode worker et la configuration Caddyfile avancée sont hors périmètre et négociables pour un sprint ultérieur.
- **Valuable :** Reproductibilité de l'environnement = prerequis pour tout développement collaboratif et CI fiable.
- **Estimable :** 3 points ; configuration Docker standard, image officielle disponible, risque faible.
- **Small :** Périmètre strictement limité à Docker + FrankenPHP ; pas de TLS, pas de prod.
- **Testable :** Health-check Docker, code HTTP 200, version PHP vérifiable en une commande.

## Dépendances
- **Dépend de :** US-001
- **Bloque :** US-004 (le layout nécessite un serveur pour être testé bout en bout)

## Definition of Done
Voir `project-management/definition-of-done.md` (fidélité TailAdmin, dark mode, responsive, a11y WCAG AA, PHPStan max, tests ≥80%, Stimulus, séparation bundle/démo, doc).
