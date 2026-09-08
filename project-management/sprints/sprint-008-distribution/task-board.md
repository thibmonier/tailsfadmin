# Task Board — Sprint 008 (Distribution & consommabilité)

## Légende
🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué

## 🔲 À Faire

| ID | US | Tâche | Est. |
|----|-----|-------|------|
| T-031-05 | US-031 | Soumission Packagist + webhook + tag | 1.5h | ⚠️ action sortante/manuelle (mainteneur) |
| T-031-06 | US-031 | Vérif require app vierge + review | 0.5h | après T-031-05 |
| T-029-04 | US-029 | Vérif live recette via app vierge | 2.5h | 🟡 partiel (gardes CI faites) — e2e après canal actif (Packagist) |


## 🔄 En Cours
| ID | US | Tâche | Démarré |
|----|-----|-------|---------|
| — | — | — | — |

## 👀 En Review
| ID | US | Tâche | Livrable |
|----|-----|-------|----------|
| — | — | — | — |

## ✅ Terminé
| ID | US | Tâche | Terminé |
|----|-----|-------|---------|
| T-027-01 | US-027 | Spike + ADR-007 (option a écartée factuellement) | ADR-007 accepté |
| T-027-02 | US-027 | Manifeste des pins (hors demo/) | `config/importmap-entries.php` |
| T-027-03 | US-027 | Commande `tailsfadmin:assets:install` + planner | `src/Command/AssetsInstallCommand.php`, `src/Assets/*` |
| T-027-04 | US-027 | Préflight erreur explicite si lib non vendorée | `assets/controllers/assets_check_controller.js` |
| T-027-05 | US-027 | Path AssetMapper vendor-src + synchro package.json | `prepend()`, `package.json`, `assets/package.json` |
| T-027-06 | US-027 | Tests fonctionnels du câblage assets | `tests/Functional/AssetsWiringTest.php` |
| T-027-07 | US-027 | Doc procédure d'install assets | `README.md` §1 Assets |
| T-027-08 | US-027 | Review (PHPStan max, CS, 28 tests verts) | DoD ✅ |
| T-028-01 | US-028 | Point d'entrée CSS `@import`-able (`theme.css`) | `assets/styles/theme.css` |
| T-028-02 | US-028 | `@source` templates portables (build vérifié) | `.menu-item*` générées |
| T-028-03 | US-028 | Variables `--color-brand-*` surchargeables | cascade `:root` prouvée |
| T-028-04 | US-028 | Doc intégration Tailwind hôte | `README.md` §2 Thème CSS |
| T-028-05 | US-028 | Spec smoke CSS (exécutée US-030) | `specs/css-smoke-spec.md` |
| T-028-06 | US-028 | Review CSS + build démo vert | DoD ✅ |
| T-030-01 | US-030 | Script app vierge + install (archive dist) | `tests/integration/create-app.sh` |
| T-030-02 | US-030 | Étapes documentées appliquées | `tests/integration/apply-steps.sh` |
| T-030-03 | US-030 | Smoke nominal (layout + Dropdown monté) | `fixtures/SmokeTest.php` |
| T-030-04 | US-030 | Scénario échec vendoring (marqueur DOM) | `fixtures/SmokeTest.php` |
| T-030-05 | US-030 | Job CI `integration` matrice 7.3/8.0 | `.github/workflows/ci.yml` |
| T-030-06 | US-030 | Review — CI `integration` VERTE (PR #1) | ✅ 5/5 jobs verts |
| T-031-01 | US-031 | Nettoyage métadonnées composer.json (version retirée, deps, support) | `composer validate --strict` ✅ |
| T-031-02 | US-031 | LICENSE MIT + package.json clarifié | `LICENSE` |
| T-031-03 | US-031 | `composer validate --strict` bloquant en CI | job `bundle` |
| T-031-04 | US-031 | Badges README + URL canonique harmonisée | `README.md` |
| T-029-01 | US-029 | Manifest recette Flex (bundle + config + post-install) | `recipe/.../manifest.json` |
| T-029-02 | US-029 | Canal recette + dégradation sans AssetMapper | `recipe/README.md` |
| T-029-03 | US-029 | Idempotence (copy-from-recipe, fichier assets dédié) | `recipe/README.md` |
| T-029-05 | US-029 | Doc recette + procédure de contribution | `recipe/README.md` |
| T-029-06 | US-029 | Review (32 tests verts, gardes manifeste) | `RecipeManifestTest` |

## 🚫 Bloqué
| ID | US | Raison | Action |
|----|-----|--------|--------|
| — | — | — | — |

## Métriques
- **Tâches engagées** : 26 · **2 terminées** · 4 en review (T-027-03→06) · reste US-027 : 07 doc + 08 review
- **Points** : 21 engagés (US-027 8 + US-028 5 + US-030 5 + US-031 3) · US-029 5 en réserve
- **Heures** : 47h engagées estimées · ~16.5h consommées (T-027-01→06) · ~30.5h restantes
- **Qualité** : bundle **28/28** (23 unit + 5 fonctionnels) · PHPStan max 0 · cs-fixer 0 · démo 180/180 (non-régression) · `asset-map:compile` OK · smoke `assets:install` idempotent (11 entrées, exit 0)
- **Stretch** : US-029 (6 tâches · 11h) si capacité

> Sprint 8 en cours. US-027 quasi complète : T-027-01→06 **livrés** (ADR-007, manifeste, commande + planner TDD, préflight erreur console, path vendor-src + synchro package.json, tests fonctionnels). Reste T-027-07 (doc) + T-027-08 (review) avant commit `feat(assets)`.
