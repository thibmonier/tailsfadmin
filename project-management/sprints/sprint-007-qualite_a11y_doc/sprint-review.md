# Sprint Review — Sprint 007 (Qualité, accessibilité & livrabilité)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-08 |
| Sprint | 007 — Finition & livrabilité (dernier sprint, EPIC-007) |
| Contexte | Projet solo — inspection de l'incrément |

## Sprint Goal

> **Transformer le thème en un bundle professionnel adoptable : garantir
> l'accessibilité WCAG 2.2 AA de toutes les pages (clavier, lecteur d'écran,
> contrastes clair/dark/RTL), et livrer la chaîne de qualité et de documentation
> (CI complète, README, catalogue de composants, CHANGELOG SemVer).**

**Atteint : ✅ OUI** — 0 violation axe-core (A/AA) sur toutes les pages en clair
et dark ; CI étendue (couverture, E2E+axe, lint front) ; documentation de
livrabilité complète.

## User Stories livrées

| ID | Titre | Points | Statut |
|----|-------|--------|--------|
| US-025 | Accessibilité WCAG 2.2 AA (transversal) | 5 | ✅ Livré |
| US-026 | CI, documentation & livrabilité | 8 | ✅ Livré |

**Livré : 13/13 points (100 %)**

## Métriques

| Métrique | Valeur |
|----------|--------|
| Points engagés / livrés | 13 / 13 |
| Vélocité | 13 (S1 21, S2 21, S3 19, S4 18, S5 24, S6 23, **S7 13**) |
| Tests | **180 fonctionnels + 14 E2E Panther** (dont audit axe-core) |
| Violations WCAG A/AA (axe) | **0** (dashboard, profil+modale, auth, page vierge — clair & dark) |
| PHPStan (max) | 0 erreur |
| php-cs-fixer | 0 |
| Biome (front) | 0 |
| Régression | 0 |

## Démonstration (reproductible)

```bash
cd demo && composer test          # 180/180 fonctionnels
composer test:e2e                 # 14/14 Panther (montage JS + audit a11y axe-core)
npx @biomejs/biome check assets/controllers/   # lint front (bundle)
```
- **Accessibilité** : navigation clavier complète, sous-menus sidebar repliables
  (`aria-expanded`), focus visible, Échap ferme les modales (focus restitué),
  contrastes AA vérifiés en clair ET dark.
- **CI** (`.github/workflows/ci.yml`) : jobs bundle (PHPStan + cs-fixer + tests +
  couverture ≥ 80 %), front-lint (Biome), démo, e2e (Chrome + axe-core).
- **Doc** : `README.md` (config/quickstart), `docs/components.md` (catalogue),
  `docs/adr/0006`, `CHANGELOG.md` 1.0.0.

## Incrément produit

- **Accessibilité (US-025)** : helper `AxeAudit` réutilisable ; contrôleur
  `tailsfadmin--submenu` (sous-menus repliables) ; corrections ARIA/contraste/focus
  dans le bundle ; garde anti-régression dark.
- **Livrabilité (US-026)** : CI complète et bloquante ; `biome.json` ; documentation
  d'installation, catalogue de composants, ADR-006, CHANGELOG ; `composer.json` 1.0.0.

## Feedback / décisions

### Positif
- **Audit automatisé décisif** : axe-core a transformé « l'accessibilité » (souvent
  subjective) en critère binaire et reproductible ; 0 violation prouvée en CI.
- **Corrections à la racine** : contrastes traités dans les composants du bundle
  (Badge, Button, menu) → bénéficient à tout consommateur.
- **Dette dark-mode verrouillée** : une garde visuelle empêche la réapparition de
  l'inversion des gris.

### À améliorer / suivi
- **Couverture 80 %** : mesurée en CI (pcov), non vérifiable localement (ni
  pcov/xdebug ; phpdbg non supporté par PHPUnit 12). Si le premier run CI passe
  sous le seuil, compléter les tests unitaires du bundle.
- **Timing des audits** : axe doit s'exécuter APRÈS recalcul des styles (reflow +
  attente post-toggle de thème) — piège capturé dans le helper.

### Impact sur le backlog
- **Backlog vidé** : US-025 et US-026 étaient les dernières US. **Projet complet**
  (bundle tailsfadmin 1.0.0). Reste la **release** (tag v1.0.0).

## Prochaines étapes
1. ✅ Rétrospective Sprint 7 (`sprint-retro.md`) — clôture du projet.
2. PR vers `main` + CI verte.
3. Tag `v1.0.0` + release.
