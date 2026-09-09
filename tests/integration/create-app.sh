#!/usr/bin/env bash
#
# US-030 · T-030-01 — Crée une app Symfony VIERGE et y installe le bundle comme
# un tiers, via l'ARCHIVE DIST (honore .gitattributes export-ignore) → fidèle à
# ce que reçoit un consommateur Packagist, contrairement à la démo (path repo
# couplé). L'app est éphémère (recréée à chaque run CI, hors du monorepo).
#
# Usage : create-app.sh <app_dir> <symfony_version>   (ex. /tmp/host-app 7.3)
set -euo pipefail

APP_DIR="${1:?usage: create-app.sh <app_dir> [symfony_version]}"
# Version Symfony cible optionnelle. Vide (défaut) → dernière stable, ce que le
# bundle supporte via « ^8.0 » (skeleton actuel = 8.x). Une valeur (ex. 7.3)
# force la ligne via Flex — utile pour une matrice, mais dépend d'un Flex global
# actif ; laissée en option car le skeleton courant épingle sa propre version.
SF_VERSION="${2:-}"

BUNDLE_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
DIST_DIR="$(dirname "$APP_DIR")/bundle-dist"

echo "==> 1/4 Archive dist du bundle (export-ignore honoré par composer archive)"
rm -rf "$DIST_DIR"
mkdir -p "$DIST_DIR"
( cd "$BUNDLE_ROOT" && composer archive --format=tar --dir="$DIST_DIR" --file=bundle )
mkdir -p "$DIST_DIR/pkg"
tar -xf "$DIST_DIR/bundle.tar" -C "$DIST_DIR/pkg"
# Version explicite : l'archive n'embarque pas le tag git, le path repository en
# a besoin pour résoudre la contrainte « ^1.0 » (ligne de version courante : 1.2.x).
( cd "$DIST_DIR/pkg" && composer config version "1.2.0" )

echo "   Contenu distribué :"
ls -1 "$DIST_DIR/pkg"
# Garde-fou fidélité : ces répertoires NE doivent PAS être distribués (ADR-002).
for excluded in demo tests docs project-management Tools .github; do
  if [ -e "$DIST_DIR/pkg/$excluded" ]; then
    echo "ERREUR: « $excluded » présent dans l'archive dist — export-ignore cassé." >&2
    exit 1
  fi
done

echo "==> 2/4 Création de l'app Symfony ${SF_VERSION:-(dernière stable)} (skeleton)"
if [ -n "$SF_VERSION" ]; then
  # Forçage d'une ligne Symfony précise (matrice). Nécessite Flex global pour que
  # SYMFONY_REQUIRE réécrive les contraintes du squelette. Cf. doc Symfony Flex.
  export SYMFONY_REQUIRE="${SF_VERSION}.*"
  composer global config --no-plugins allow-plugins.symfony/flex true
  composer global require --no-interaction --no-progress --no-scripts symfony/flex
fi
composer create-project symfony/skeleton "$APP_DIR" --no-interaction --no-progress
cd "$APP_DIR"
composer config extra.symfony.allow-contrib true
composer config minimum-stability stable
composer config prefer-stable true

echo "==> 3/4 Composants hôte (AssetMapper, Stimulus, Tailwind, Twig, i18n, Panther)"
composer require --no-interaction --no-progress \
  symfony/asset-mapper symfony/stimulus-bundle symfonycasts/tailwind-bundle \
  symfony/twig-bundle twig/extra-bundle symfony/translation
composer require --dev --no-interaction --no-progress symfony/panther phpunit/phpunit

echo "==> 4/4 Installation du bundle via l'archive dist (path repository, sans symlink)"
composer config repositories.tailsfadmin \
  "{\"type\":\"path\",\"url\":\"$DIST_DIR/pkg\",\"options\":{\"symlink\":false}}"
composer require --no-interaction --no-progress "tailsfadmin/tailsfadmin-bundle:^1.0"

echo "==> App tierce prête : $APP_DIR"
