# Sprint Review — Sprint 001 (Walking Skeleton)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-07 |
| Sprint | 001 — Walking Skeleton |
| Animateur | Scrum Master (assisté) |
| Contexte | Projet solo (dev unique) — review = inspection de l'incrément |

## Sprint Goal

> **Prouver la chaîne technique de bout en bout : une app de démo qui démarre sous FrankenPHP/PHP 8.5, consommant un bundle Symfony réutilisable, avec un layout admin rendu par Tailwind v4 (via AssetMapper) et un premier contrôleur Stimulus (preloader) packagé depuis le bundle.**

**Atteint : ✅ OUI**

Justification : l'incrément traverse effectivement toutes les couches (bundle → démo → assets → runtime → UI interactive). Le risque technique n°1 (Tailwind v4 ⇄ AssetMapper) est levé par un POC réel. La distribution d'un contrôleur Stimulus depuis le bundle vers la démo est prouvée (`data-controller="tailsfadmin--preloader"`).

## User Stories livrées

| ID | Titre | Points | Démo | Statut |
|----|-------|--------|------|--------|
| US-001 | Squelette bundle + app de démo | 5 | ✅ | ✅ Livré |
| US-002 | AssetMapper + Tailwind v4 + tokens | 8 | ✅ | ✅ Livré |
| US-003 | Runtime FrankenPHP / PHP 8.5 | 3 | ✅ | ✅ Livré |
| US-004 | Layout admin de base | 5 | ✅ | ✅ Livré |

**Livré : 21/21 points engagés (100 %)**

> US-005 (dark mode, 5 pts) a été **retirée du périmètre pendant la planification** (ajustement de charge), pas échouée en cours de sprint. Reportée au Sprint 2.

## Métriques

| Métrique | Valeur | Note |
|----------|--------|------|
| Points engagés | 21 | après retrait d'US-005 |
| Points livrés | 21 | 100 % du périmètre engagé |
| Vélocité | 21 | 1er sprint (référence) |
| Tâches | 31/31 | 100 % |
| Tests | 15/15 verts | bundle 2 + démo 13 (35 assertions) |
| PHPStan (niveau max) | 0 erreur | sur `src/` |
| php-cs-fixer | 0 correction | PSR-12 + strict types |
| Bugs bloquants | 0 | 1 échec transitoire de test résolu (hash AssetMapper stale) |

## Démonstration (reproductible)

### 1. L'app démarre et rend le layout — US-001/004
```bash
cd demo && php -S 127.0.0.1:8000 -t public
# GET / → 200, layout admin (header, sidebar, main#main-content, breadcrumb "Dashboard"), skip-link
```

### 2. Pipeline Tailwind v4 sans Node — US-002
```bash
cd demo && php bin/console tailwind:build   # binaire v4.3.3 standalone, ~70ms
php bin/console asset-map:compile           # CSS compilé contient .dark + bg-brand-500
```

### 3. Runtime FrankenPHP via Docker — US-003
```bash
docker compose up --build -d                # conteneur healthy
curl -s -o /dev/null -w '%{http_code}' http://localhost/   # 200
docker compose exec demo frankenphp version # FrankenPHP v1.12.7 PHP 8.5.9
docker compose down
```

### 4. Contrôleur Stimulus distribué depuis le bundle — US-004 / ADR-003
```bash
# Le HTML rendu contient data-controller="tailsfadmin--preloader"
# (contrôleur fourni par le bundle, chargé côté démo via importmap)
```

### 5. Qualité
```bash
vendor/bin/phpstan analyse        # 0 erreur (niveau max)
vendor/bin/phpunit                # bundle 2/2
cd demo && php bin/phpunit        # démo 13/13
```

## Incrément produit

- **Bundle** `tailsfadmin/tailsfadmin-bundle` : `TailsfadminBundle` (AbstractBundle), service `BundleInfo`, namespace Twig `@Tailsfadmin`, composants `tsf:Layout:{Header,Sidebar,Breadcrumb}`, `tsf:Ui:Preloader`, contrôleur Stimulus `preloader`, CSS source (tokens TailAdmin + `.dark`).
- **Démo** : app Symfony 8.1.6 consommant le bundle (path repository), `HomeController`, layout hérité, pipeline Tailwind + AssetMapper, Docker/FrankenPHP.
- **Outillage** : PHPStan max, php-cs-fixer, PHPUnit 12, CI GitHub Actions.
- **Docs** : ADR-001 & ADR-003 enrichis des résultats d'implémentation réels.

## Feedback / décisions

### Positif
- Risque n°1 (Tailwind v4 ⇄ AssetMapper) levé dès le Sprint 1 — dérisque tout le backlog.
- Distribution de composants Twig + contrôleur Stimulus depuis le bundle validée (réutilisabilité prouvée).
- Chaîne 100 % sans Node pour le CSS (binaire standalone).

### À améliorer / points de suivi
- **Footgun DX** : `tailwind:build` doit être suivi de `asset-map:compile` (hash stale sinon). → durcir en US-026 (script `composer test` enchaînant build → compile → tests).
- **Caveat ADR-003** : l'app hôte doit ajouter 1 ligne d'`asset_mapper.yaml` pour les contrôleurs du bundle (le `prepend()` ne propage pas ce chemin). → à documenter dans le guide d'installation (US-026).
- **Pest écarté** : PHPUnit 12 seul (Pest 4 incompatible). Stack cible « Pest 4 / PHPUnit 12 » contradictoire → à corriger dans la doc projet.

### Impact sur le backlog
| Action | US | Description |
|--------|-----|-------------|
| Reportée | US-005 | Dark mode → Sprint 2 |
| Enrichie | US-026 | Ajouter : script `composer test`, doc du caveat asset_mapper |

## Prochaines étapes
1. Rétrospective (`/workflow:retro`).
2. Commit du Walking Skeleton (Conventional Commits).
3. Sprint 2 : US-005 (dark mode), US-006 (sidebar interactive), US-007 (header).
