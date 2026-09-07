# Task Board — Sprint 003 (UI Kit)

## Légende
🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué

## 🔲 À Faire

_(vide — toutes les tâches du Sprint 3 sont terminées)_

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
| T-TECH-01 | — | Smoke-test CSS compilé (lit le manifest, assert .menu-item + flex + fixed) | 1.5h | 2026-09-07 |
| T-008-01→05 | US-008 | `tsf:Ui:Alert` (4 variantes) + contrôleur alert-dismiss | 7.5h | 2026-09-07 |
| T-009-01→04 | US-009 | `tsf:Ui:Badge` (6) + `tsf:Ui:Avatar` (4 tailles + statut) | 6.5h | 2026-09-07 |
| T-010-01→04 | US-010 | `tsf:Ui:Button` (6 variantes, icônes, disabled, loading, href) | 6.5h | 2026-09-07 |
| T-012-01→04 | US-012 | `tsf:Ui:Dropdown` (réutilise contrôleur US-007) — 7 tests | 6.5h | 2026-09-07 |
| T-011-01→05 | US-011 | Contrôleur `modal` + `tsf:Ui:Modal` (focus trap, Échap, overlay) — 9 tests, vérifié navigateur | 11h | 2026-09-07 |
| T-TECH-03 | — | Page galerie `/ui-kit` (UiKitController) — vérifiée visuellement (clair+dark) | 2h | 2026-09-07 |
| T-TECH-02 | — | Spike Panther : 2 tests E2E navigateur (modale Échap + toggle dark) — 2/2, isolés de composer test | 3h | 2026-09-07 |

## 🚫 Bloqué
| ID | US | Raison | Action |
|----|----|--------|--------|
| — | — | — | — |

## Métriques — ✅ SPRINT 3 TERMINÉ
- **Tâches** : 25/25 (100 %)
- **Points** : **19/19 livrés** (US-008, 009, 010, 011, 012)
- **Qualité** : composer test **78/78** + **2 E2E Panther** · PHPStan max 0 · cs-fixer 0
- **2 garde-fous de l'incident CSS en place** : smoke-test du CSS compilé (T-TECH-01) + tests navigateur Panther (T-TECH-02)
- **Vérifié visuellement** : galerie `/ui-kit` (clair+dark) + modale ouverte/fermée (Échap) en vrai navigateur
- **Livré** : Alert, Badge, Avatar, Button, Dropdown, Modal (`tsf:Ui:*`) — accessibles, dark mode, 100 % Stimulus
