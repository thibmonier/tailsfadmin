# tailsfadmin

Thème admin Symfony basé sur TailAdmin — bundle réutilisable (Symfony UX, Stimulus, AssetMapper, Tailwind CSS v4).

## Architecture du monorepo

Ce dépôt suit la structure définie dans **ADR-002** :

```
tailsfadmin/
├── src/                      # TailsfadminBundle (le produit, distribué sur Packagist)
│   ├── TailsfadminBundle.php # AbstractBundle (Symfony >= 6.1)
│   └── Info/                 # Services du bundle
├── config/
│   └── services.yaml         # Services DI du bundle (préfixe tailsfadmin.*)
├── templates/                # Templates Twig namespacés @Tailsfadmin
├── assets/
│   └── controllers/          # Contrôleurs Stimulus du bundle (ADR-003)
├── demo/                     # Application de démo (consomme le bundle en local)
│   ├── src/Controller/       # Contrôleurs de la démo (usage seul)
│   └── templates/            # Templates de la démo
├── tests/                    # Tests du bundle (unitaires)
├── docs/adr/                 # Architecture Decision Records
└── composer.json             # Package du bundle (type: symfony-bundle)
```

## Règle d'isolation bundle / démo (DoD §6)

> **IMPORTANT** : aucun code réutilisable ne doit vivre dans `demo/`.
> La démo ne contient que de l'**usage** du bundle.
> Toute logique partageable appartient à `src/`.

## Prérequis

- PHP >= 8.5
- Composer >= 2.0
- Symfony >= 7.3 || 8.x

## Installation (développement — path repository)

```bash
# 1. Cloner le dépôt
git clone https://github.com/tailsfadmin/tailsfadmin-bundle.git
cd tailsfadmin-bundle

# 2. Installer les dépendances du bundle
composer install

# 3. Installer la démo
cd demo
composer install

# 4. Lancer la démo (Symfony CLI)
symfony serve -d
# ou : php -S localhost:8000 -t public
```

## Installation depuis Packagist (projet tiers)

```bash
composer require tailsfadmin/tailsfadmin-bundle
```

Le bundle est activé automatiquement par Symfony Flex.

## Commandes de développement

```bash
# Analyse statique (niveau max)
composer phpstan

# Vérification du style PSR-12
composer cs

# Correction automatique du style
composer cs-fix

# Tests unitaires du bundle
composer test

# Tests fonctionnels de la démo
cd demo && php bin/phpunit
```

## Vérifications DoD

```bash
# Conteneur DI — doit lister ≥ 1 service tailsfadmin.*
cd demo && php bin/console debug:container tailsfadmin.bundle_info

# Warmup du cache — doit passer sans erreur
cd demo && php bin/console cache:warmup
```

## Docker / FrankenPHP

La démo peut aussi être lancée via Docker Compose, sous FrankenPHP / PHP 8.5,
pour garantir un environnement reproductible (local = CI). Voir
`demo/Dockerfile` et `compose.yaml` pour le détail de l'image.

### Démarrer / arrêter

```bash
# Construire l'image et démarrer le conteneur en arrière-plan
docker compose up --build -d

# Suivre les logs
docker compose logs demo -f

# Arrêter et supprimer le conteneur
docker compose down
```

L'application est ensuite disponible sur <http://localhost/>.

### Vérifier le conteneur

```bash
# Statut du healthcheck (doit passer à "healthy")
docker compose ps

# Version de PHP et de FrankenPHP dans le conteneur
docker compose exec demo php -v
docker compose exec demo frankenphp version
```

### Changer le port hôte (erreur « port déjà utilisé »)

Si le port 80 (ou 443) est déjà occupé sur la machine hôte, définir
`HOST_HTTP_PORT` / `HOST_HTTPS_PORT` avant de lancer la commande :

```bash
HOST_HTTP_PORT=8080 HOST_HTTPS_PORT=8443 docker compose up --build -d
# → application disponible sur http://localhost:8080/
```

Ces variables peuvent aussi être placées dans un fichier `.env` à la racine
du dépôt (non versionné) pour éviter de les répéter à chaque commande.

### Utilisation hors-ligne (pré-pull de l'image)

L'image de base `dunglas/frankenphp:php8.5` peut être téléchargée à l'avance,
par exemple avant un déplacement sans accès réseau ou pour accélérer un
premier build :

```bash
docker pull dunglas/frankenphp:php8.5
```

Le `docker compose up --build` suivant réutilisera l'image déjà présente en
cache local pour l'étape `FROM`, sans nouveau téléchargement.

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Backend | PHP 8.5, Symfony 8.1, AbstractBundle |
| Frontend | Stimulus / Symfony UX, AssetMapper |
| CSS | Tailwind CSS v4 |
| Tests | PHPUnit 12, WebTestCase |
| Qualité | PHPStan max, PHP CS Fixer (PSR-12) |

## Licence

MIT — voir [LICENSE](LICENSE)
