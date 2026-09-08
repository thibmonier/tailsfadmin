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

## Configuration & prise en main

### 1. Assets (AssetMapper + importmap)

Les contrôleurs Stimulus du bundle sont exposés automatiquement sous
`bundles/tailsfadmin` (via `prepend()`, aucune config à écrire). Stimulus doit
être démarré côté hôte (`assets/bootstrap.js` du skeleton).

**Dépendances JS tierces — commande d'installation.** Cinq contrôleurs du bundle
s'appuient sur des bibliothèques tierces (ApexCharts, jsvectormap, flatpickr,
Dropzone, FullCalendar). Un bundle **ne peut pas** injecter d'entrées dans
l'`importmap.php` de l'hôte (l'importmap n'est pas une config mergeable — voir
**[ADR-007](docs/adr/0007-propagation-importmap-bundle.md)**). Le bundle fournit
donc une commande qui ajoute ces pins et **vendore les fichiers localement** :

```bash
php bin/console tailsfadmin:assets:install
```

- **Sans CDN au runtime** : les libs sont téléchargées à l'installation puis
  servies en local (ADR-004/006). La source de vérité des versions pinées est
  `config/importmap-entries.php`, distribué avec le bundle.
- **Idempotente** : relançable sans risque, n'ajoute que ce qui manque.
- **Conflits de version** : si votre app a déjà piné une lib dans une autre
  version, la commande **ne l'écrase pas** ; elle signale le conflit. Forcez la
  réécriture avec `--force`.
- **Garde-fou** : si une page utilise un composant dont la lib n'est pas
  installée, une **erreur explicite en console** rappelle la commande à lancer
  (au lieu du cryptique « Failed to resolve module specifier »).

Compilez ensuite les assets :

```bash
php bin/console tailwind:build   # CSS Tailwind v4 (symfonycasts/tailwind-bundle)
php bin/console asset-map:compile
```

> Les contrôleurs sans dépendance externe (`theme`, `sidebar`, `modal`,
> `dropdown`, `alert-dismiss`, `preloader`, `search`, `submenu`) fonctionnent
> sans cette commande, via le seul path AssetMapper.

### 2. Menu de la sidebar (`config/packages/tailsfadmin.yaml`)

Les `label` sont des **clés de traduction** (voir i18n ci-dessous) ou des libellés bruts :

```yaml
tailsfadmin:
    default_locale: fr
    locales: ['fr', 'en']       # whitelist de la bascule de langue
    rtl_locales: ['ar']
    menu:
        - group: menu.groups.menu
          items:
              - { label: menu.dashboard, path: /, icon: dashboard }
              - label: menu.tables
                path: /tables
                icon: tables
                children:
                    - { label: menu.tables_basic, path: /tables/basic }
```

### 3. Layout d'une page

```twig
{% extends '@Tailsfadmin/layout/admin.html.twig' %}
{% block breadcrumb %}<twig:tsf:Layout:Breadcrumb pageName="Tableau de bord" />{% endblock %}
{% block content %}
    <twig:tsf:Ui:Card title="Bienvenue">
        <twig:block name="body">Votre première page tailsfadmin.</twig:block>
    </twig:tsf:Ui:Card>
{% endblock %}
```

> Le layout attend une route nommée **`home`** (logo de la sidebar) et
> **`locale_switch`** si vous utilisez le sélecteur de langue.

### 4. Internationalisation (optionnel)

Installez `symfony/translation`, réglez `framework.default_locale` + `enabled_locales`,
et fournissez vos catalogues. Le bundle expose ses propres traductions du chrome
(header, breadcrumb…) et les helpers Twig `tsf_dir()` / `tsf_locales()`.

### 5. Catalogue des composants

Tous les composants `<twig:tsf:… />` (props, slots, exemples) sont documentés
dans **[docs/components.md](docs/components.md)** ; une galerie vivante est
servie sur `/ui-kit` dans la démo.

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

# Tests E2E (montage JS réel + audit accessibilité axe-core)
cd demo && composer test:e2e

# Lint des contrôleurs Stimulus (Biome)
npx @biomejs/biome check assets/controllers/
```

Le versionnement suit **SemVer** ; les évolutions sont consignées dans
[CHANGELOG.md](CHANGELOG.md) (format Keep a Changelog).

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
