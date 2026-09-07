# syntax=docker/dockerfile:1.7
#
# Image de la démo tailsfadmin — runtime FrankenPHP / PHP 8.5 (US-003).
#
# Contextes de build (voir compose.yaml) :
#   - contexte par défaut ...... ./demo (permet à `demo/.dockerignore`
#     de fonctionner normalement, comme pour n'importe quel projet Symfony)
#   - contexte nommé "bundle" .. racine du monorepo, injecté via
#     `build.additional_contexts` (Compose Build Spec)
#
# Pourquoi deux contextes ? `demo/composer.json` déclare le bundle
# `tailsfadmin/tailsfadmin-bundle` via un "path repository" (`"url": "../"`) :
# à l'exécution de `composer install`, le code du bundle (composer.json, src/,
# config/, templates/, assets/) doit donc être présent à côté de `demo/`, à
# l'identique de la disposition locale sur le poste de développement.
# Utiliser un second contexte nommé (plutôt qu'un contexte unique = racine du
# repo) permet de garder `demo/.dockerignore` fonctionnel et d'éviter de
# dépendre d'un `.dockerignore` à la racine du monorepo (hors périmètre de
# cette US).
#
# Arborescence recréée dans l'image :
#   /srv/               <- bundle (contexte "bundle")
#   /srv/demo/           <- démo (contexte par défaut), avec /srv/demo/../ == /srv
#   => la path repository "../" de demo/composer.json se résout correctement.
#
# Important : Composer installe cette path repository sous forme de SYMLINK
# relatif (demo/composer.json a l'option "symlink": true) : `vendor/tailsfadmin/
# tailsfadmin-bundle -> ../../..`. Ce lien ne peut être suivi que si l'image
# finale conserve exactement la même arborescence relative — d'où la copie de
# la totalité de `/srv` (et pas seulement `/srv/demo`) dans l'étape runtime,
# avec le même WORKDIR `/srv/demo`.

# ==============================================================================
# Étape 1 — Binaire Composer (récupéré depuis l'image officielle, non exécutée)
# ==============================================================================
FROM composer:2.8 AS composer_bin

# ==============================================================================
# Étape 2 — Build : installation des dépendances PHP (bundle + démo)
# ==============================================================================
FROM dunglas/frankenphp:php8.5 AS build

# Extensions PHP nécessaires :
#   - intl    : utilisée par les composants Symfony (Validator, Translation…)
#   - opcache : cache d'opcodes, indispensable en production
#   - zip     : requise par Composer pour dézipper les paquets
#               (l'image de base ne fournit pas la commande `unzip`)
RUN install-php-extensions intl opcache zip

COPY --from=composer_bin /usr/bin/composer /usr/bin/composer

WORKDIR /srv

# --- Code du bundle (contexte nommé "bundle" = racine du monorepo) ----------
# Seuls les répertoires réellement nécessaires à l'autoload/à Composer sont
# copiés (pas de vendor/, tests/, docs/… du bundle).
COPY --from=bundle composer.json composer.lock ./
COPY --from=bundle src/ ./src/
COPY --from=bundle config/ ./config/
COPY --from=bundle templates/ ./templates/
COPY --from=bundle assets/ ./assets/

# --- Dépendances de la démo (contexte par défaut = demo/) -------------------
# On ne copie d'abord que les manifestes Composer pour profiter du cache
# Docker : les dépendances ne sont réinstallées que si composer.json/.lock
# changent, pas à chaque modification du code de la démo.
COPY composer.json composer.lock symfony.lock ./demo/

WORKDIR /srv/demo
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-interaction \
        --no-progress \
        --optimize-autoloader \
        --prefer-dist

# --- Reste du code de la démo -------------------------------------------
COPY . ./

ENV APP_ENV=prod

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative \
    && php bin/console cache:warmup --env=prod --no-debug

# ==============================================================================
# Étape 3 — Image finale (runtime minimal, sans outillage de build)
# ==============================================================================
FROM dunglas/frankenphp:php8.5 AS runtime

RUN install-php-extensions intl opcache zip

# SERVER_NAME=:80 force le Caddyfile par défaut de l'image FrankenPHP à servir
# en HTTP simple, sans tenter de mise en place automatique de HTTPS (qui
# provoquerait une redirection sur `curl http://localhost/`, cf. compose.yaml).
# SERVER_ROOT est relatif au WORKDIR : avec WORKDIR=/srv/demo, Caddy sert
# donc /srv/demo/public (racine web standard d'un projet Symfony).
ENV APP_ENV=prod \
    SERVER_NAME=:80

WORKDIR /srv/demo

# On copie /srv en entier (bundle + démo), pas seulement /srv/demo : voir la
# note sur le symlink Composer ci-dessus.
COPY --from=build /srv /srv
