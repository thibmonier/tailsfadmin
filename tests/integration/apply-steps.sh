#!/usr/bin/env bash
#
# US-030 · T-030-02 — Applique les étapes d'intégration DOCUMENTÉES (README) à
# l'app vierge : entrée Tailwind hôte important le thème (US-028), config du
# menu + routes, et pose les fixtures de smoke (page admin, page chart, test).
# NE lance PAS `tailsfadmin:assets:install` : le job CI le fait entre la phase
# « échec » (T-030-04) et la phase « nominal » (T-030-03).
#
# US-029 (recette Flex) non disponible → étapes manuelles, conformément à la US.
#
# Usage : apply-steps.sh <app_dir>
set -euo pipefail

APP_DIR="${1:?usage: apply-steps.sh <app_dir>}"
FIX="$(cd "$(dirname "${BASH_SOURCE[0]}")/fixtures" && pwd)"
cd "$APP_DIR"

echo "==> US-028 : l'entrée Tailwind de l'hôte importe le thème du bundle (1 ligne)"
mkdir -p assets/styles
cat > assets/styles/app.css <<'CSS'
/* App hôte de test — pattern d'intégration US-028 (thème distribué du bundle). */
@import "tailwindcss";
@import "../../vendor/tailsfadmin/tailsfadmin-bundle/assets/styles/theme.css";
CSS

echo "==> Config binaire Tailwind (la recette ne pose pas toujours binary_version)"
cat > config/packages/symfonycasts_tailwind.yaml <<'YAML'
symfonycasts_tailwind:
    binary_version: v4.3.3
YAML

echo "==> Config + fixtures (menu/i18n, routes home + locale_switch, pages, test)"
cp "$FIX/tailsfadmin.yaml" config/packages/tailsfadmin.yaml
mkdir -p src/Controller templates tests
cp "$FIX/HomeController.php" src/Controller/HomeController.php
cp "$FIX/templates/home.html.twig" templates/home.html.twig
cp "$FIX/templates/chart.html.twig" templates/chart.html.twig
cp "$FIX/SmokeTest.php" tests/SmokeTest.php
cp "$FIX/phpunit.xml.dist" phpunit.xml.dist

echo "==> Étapes d'intégration appliquées à $APP_DIR"
