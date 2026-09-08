# Task Board — Sprint 008 (Distribution & consommabilité)

## Légende
🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué

## 🔲 À Faire

| ID | US | Tâche | Est. |
|----|-----|-------|------|
| T-030-01 | US-030 | Script création app vierge + install | 3h |
| T-030-02 | US-030 | Appliquer étapes documentées | 2h |
| T-030-03 | US-030 | Smoke : layout admin + JS monté | 3h |
| T-030-04 | US-030 | Scénario d'échec vendoring | 1.5h |
| T-030-05 | US-030 | Job CI `integration` bloquant | 1.5h |
| T-030-06 | US-030 | Review | 0.5h |
| T-031-01 | US-031 | Nettoyage métadonnées composer.json | 2h |
| T-031-02 | US-031 | LICENSE + alignement package.json | 1h |
| T-031-03 | US-031 | `composer validate --strict` CI | 1h |
| T-031-04 | US-031 | Badges README | 1h |
| T-031-05 | US-031 | Soumission Packagist + webhook + tag | 1.5h |
| T-031-06 | US-031 | Vérif require app vierge + review | 0.5h |

### 🟣 Réserve / stretch (US-029)
| ID | US | Tâche | Est. |
|----|-----|-------|------|
| T-029-01 | US-029 | Manifest recette Flex | 3h |
| T-029-02 | US-029 | Canal recette + dégradation | 2h |
| T-029-03 | US-029 | Idempotence | 2h |
| T-029-04 | US-029 | Vérif via app vierge | 2.5h |
| T-029-05 | US-029 | Doc recette + contribution | 1h |
| T-029-06 | US-029 | Review | 0.5h |

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
