# Tâches techniques transverses — Sprint 008

> La plupart des tâches d'outillage sont rattachées aux US (mécanisme importmap → US-027 ;
> CSS distribuable → US-028 ; job CI intégration → US-030 ; métadonnées/Packagist → US-031).
> Ce fichier note les décisions transverses et la **dette de distribution** identifiée à l'audit.

## Décisions actées

| Sujet | Décision | Rattachement |
|-------|----------|--------------|
| Canal de distribution | **Packagist d'abord**, publication après US-030 verte | US-031 |
| Assets | **Sans Node / sans CDN** conservé (ADR-001/004/006) | US-027, US-028 |
| Mécanisme importmap | À arbitrer par spike → **ADR-007** (prepend vs commande vs importmap partiel) | T-027-01 |
| Preuve d'intégration | Test sur **app réelle vierge** en CI plutôt que couverture symlink/path | US-030 |
| Recette Flex | **Stretch**, contrib `recipes-contrib` seulement après Packagist | US-029 |

## Dette de distribution identifiée (audit) → rattachements

| Constat (audit bundle) | Rattachement |
|------------------------|--------------|
| `version: "1.0.0"` **figée** dans `composer.json` (doit venir des tags Git ; contredit `branch-alias 1.x-dev`) | T-031-01 |
| Fichier **`LICENSE` absent** (README pointe vers `[LICENSE]`, composer déclare MIT) | T-031-02 |
| `package.json` racine **désynchronisé** : `0.1.0`, 1 seul contrôleur (`preloader`) vs `assets/package.json` (13 contrôleurs) | T-027-05, T-031-02 |
| **URLs GitHub incohérentes** : homepage composer `thibmonier/tailsfadmin` ≠ clone README `tailsfadmin/tailsfadmin-bundle` | T-031-01 |
| **Dépendances implicites** absentes du `require` : `symfony/asset-mapper`, `stimulus-bundle`, `translation` | T-031-01 |
| `prepend()` AssetMapper **ne survit pas au merge** (démo re-déclare le path dans `asset_mapper.yaml`) | T-027-01, T-027-05 |
| Pins importmap dans `demo/importmap.php` (**export-ignore** → non fournis au tiers) | US-027 |
| CSS source `assets/styles/app.css` **non exposé** par AssetMapper (import à documenter) | US-028 |
| `src/DependencyInjection/Configuration.php` **redondant/mort** (AbstractBundle utilise `configure()`) | Nettoyage opportuniste (T-027-03 / hors périmètre) |
| **Aucun job CI de release** (pas de `composer validate --strict`, pas de tag→Packagist) | T-030-05, T-031-03/05 |
| README **sans badge** | T-031-04 |

## Périmètre CI (existant → cible)

- **Existant** (`.github/workflows/ci.yml`, 4 jobs) : `bundle` (PHPStan max + cs-fixer + PHPUnit + couverture clover, **seuil ≥80 % non bloquant**), `front-lint` (Biome), `demo` (build CSS + asset-map + fonctionnels), `e2e` (Panther + Chrome + axe-core).
- **Cible S8** : + job **`integration`** (app Symfony vierge, smoke test tiers — US-030, bloquant PR), + étape **`composer validate --strict`** (US-031), + **badges** README.

## ADR à produire

- **ADR-007** — Mécanisme de propagation de l'importmap du bundle vers l'hôte (T-027-01).

## Vérification finale (definition of done sprint)

- [ ] `composer require` sur app vierge → back-office stylé + interactif (US-030 verte).
- [ ] Assets JS fournis par le bundle sans recopie de `demo/importmap.php` (US-027).
- [ ] Thème CSS importable en 1 ligne + brand surchargeable + dark OK (US-028).
- [ ] `composer.json` sans `version` figée, `LICENSE` présent, badges + `composer validate --strict` vert (US-031).
- [ ] Package publié sur Packagist après intégration prouvée (US-031).
- [ ] (Stretch) recette Flex configurant l'app automatiquement (US-029).
