# Tâches techniques transverses — Sprint 007

> La plupart des tâches d'outillage sont rattachées aux US (axe-core → US-025 ;
> couverture/E2E/biome → US-026). Ce fichier note les décisions transverses.

## Décisions outillage (validées)

| Sujet | Décision | Rattachement |
|-------|----------|--------------|
| Lint front | **biome** (zéro-config, rapide) | T-026-03 |
| Audit a11y | **axe-core automatisé** en E2E Panther, bloquant CI | T-025-01/05, T-026-02 |
| Couverture | **PCOV** (pas Xdebug), seuil **≥ 80 %** sur `src/` bundle | T-026-01 |
| Sous-menus sidebar | Rendus **repliables** (Stimulus + `aria-expanded`), remplace le `x-data` Alpine vestigial | T-025-03 |

## Dette / actions rétro S6 intégrées

- **Garde visuelle dark** (anti-régression inversion des gris) → T-025-05.
- **ADR-006** intégration lib JS via importmap (+ manifeste `assets/package.json`) → T-026-07.
- **Doc gotchas composants** (Button attributes, `_self.macro` vs slots, `:iconStart`) → T-026-05.

## Périmètre CI (existant → cible)

- **Existant** (`ci.yml`) : job bundle (PHPStan max + cs-fixer + phpunit), job démo (build CSS + asset-map + phpunit fonctionnels).
- **Cible S7** : + couverture PCOV ≥ 80 % (T-026-01), + job E2E Panther incluant axe (T-026-02), + job biome front (T-026-03). Tous bloquants sur PR.

## Vérification finale (definition of done sprint)

- [ ] axe-core : 0 violation AA sur dashboard + profil (clair + dark).
- [ ] Navigation clavier complète + focus retour modale.
- [ ] CI verte : PHPStan, cs-fixer, tests + couverture ≥ 80 %, E2E + axe, biome.
- [ ] README + `docs/components.md` + `CHANGELOG.md` + ADR-006 rédigés.
