# Task Board — Sprint 001 (Walking Skeleton)

## Légende
🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué

## 🔲 À Faire

_(vide — toutes les tâches du Sprint 1 sont terminées)_

> **US-005 (dark mode) déplacée en Sprint 2** — tâches T-005-01→06 hors périmètre Sprint 1 (voir `tasks/US-005-tasks.md`).

## 🔄 En Cours
| ID | US | Tâche | Démarré | Assigné |
|----|----|-------|---------|---------|
| — | — | — | — | — |

## 👀 En Review
| ID | US | Tâche | Reviewer |
|----|----|-------|----------|
| — | — | — | — |

## ✅ Terminé
| ID | US | Tâche | Réel | Terminé |
|----|----|-------|------|---------|
| T-TECH-01 | — | PHPStan max + php-cs-fixer | 2h | 2026-09-07 |
| T-TECH-02 | — | PHPUnit 12 (Pest écarté, cf. note) | 2h | 2026-09-07 |
| T-TECH-04 | — | Packaging contrôleurs (package.json stub) | 2h | 2026-09-07 |
| T-001-01 | US-001 | Init monorepo + composer.json | 2h | 2026-09-07 |
| T-001-02 | US-001 | Bundle AbstractBundle + services.yaml | 3h | 2026-09-07 |
| T-001-03 | US-001 | App de démo + path repo | 3h | 2026-09-07 |
| T-001-04 | US-001 | HomeController + route / | 1h | 2026-09-07 |
| T-001-05 | US-001 | Tests (bundle 2/2 + démo 2/2) | 2h | 2026-09-07 |
| T-001-06 | US-001 | README | 1h | 2026-09-07 |
| T-001-07 | US-001 | Revue/vérif (PHPStan max, tests verts) | 1h | 2026-09-07 |
| T-002-01→08 | US-002 | Tailwind v4.3.3 + AssetMapper + tokens + POC + tests 5/5 | 18h | 2026-09-07 |
| T-003-01→05 | US-003 | Docker/FrankenPHP v1.12.7 (2 contextes build, healthy, curl 200) | 8.5h | 2026-09-07 |
| T-004-01→07 | US-004 | Layout @Tailsfadmin + composants tsf + preloader Stimulus distribué (13/13 tests) | 15h | 2026-09-07 |
| T-TECH-03 | — | CI GitHub Actions (phpstan max, cs-fixer, tests, tailwind:build) — étapes vérifiées en local | 3h | 2026-09-07 |

> **Note Pest/PHPUnit** : la stack cible « Pest 4 / PHPUnit 12 » est contradictoire (Pest 4 exige PHPUnit 11). Retenu : **PHPUnit 12.5 seul**. Décision à confirmer (cf. rapport).

## 🚫 Bloqué
| ID | US | Raison | Action |
|----|----|--------|--------|
| — | — | — | — |

## Métriques — ✅ SPRINT 1 TERMINÉ
- **Tâches** : 31 total · **31 terminées (100 %)**
- **Points** : **21/21 livrés** (US-001, US-002, US-003, US-004)
- **Qualité** : PHPStan max 0 erreur · **tests 15/15 verts** (bundle 2 + démo 13) · php-cs-fixer 0 · CI authored
- **Stack prouvée** : Symfony 8.1.6 · PHP 8.5.10 · Tailwind v4.3.3 (sans Node) · FrankenPHP v1.12.7 · Stimulus distribué depuis le bundle
- **Risque n°1 (Tailwind v4 ⇄ AssetMapper)** : ✅ **levé**
- **Report** : US-005 (dark mode, 5 pts) → Sprint 2
