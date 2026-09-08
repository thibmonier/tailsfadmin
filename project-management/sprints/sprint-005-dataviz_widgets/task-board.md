# Task Board — Sprint 005 (Data-viz & widgets riches)

## Légende
🔲 À faire · 🔄 En cours · 👀 Review · ✅ Done · 🚫 Bloqué

## 🔲 À Faire

_(vide — toutes les tâches du Sprint 5 sont terminées)_

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
| T-015-01→04 | US-015 | Datepicker flatpickr — vendoring JS+CSS (importmap type:css, 200 vérifié), dark-aware — 7 tests | 6.5h | 2026-09-08 |

| T-016-01→05 | US-016 | Upload Dropzone (v6.2.0 vendoré, url paramétrable, endpoint stub 200, dark-aware) — 10 tests | 9h | 2026-09-08 |
| T-018-01→06 | US-018 | ApexCharts v7.1.0 (contrôleur dark-aware MutationObserver, Chart:Line/Bar, 3 démos) — 11 tests | 15h | 2026-09-08 |
| T-020-01→06 | US-020 | FullCalendar v6.1.21 (vendoré, dark-aware, dateClick→modale réutilisée US-011) — 12 tests | 14h | 2026-09-08 |
| T-TECH-01 | — | Panther étendu : 3 E2E de montage JS réel (SVG ApexCharts, flatpickr ouvert, grille FullCalendar) — 5/5 e2e | 2.5h | 2026-09-08 |
| T-TECH-02 | — | Galerie /ui-kit enrichie au fil de l'eau (sections charts, datepicker, upload, calendrier + ancres) | 1.5h | 2026-09-08 |

> **Recette vendoring validée** (réutilisable) : `importmap:require <lib>/dist/lib.min.css` (type css) → AssetMapper injecte le `<link>`, zéro CDN, zéro 404.

## 🚫 Bloqué
| ID | US | Raison | Action |
|----|----|--------|--------|
| — | — | — | — |

## Métriques — ✅ SPRINT 5 TERMINÉ
- **Tâches** : 23/23 (100 %)
- **Points** : **24/24 livrés** (US-015, US-016, US-018, US-020)
- **Qualité** : composer test **157/157** + **5 E2E Panther** · PHPStan max 0 · cs-fixer 0
- **4 libs vendorées sans CDN, dark-mode aware** : flatpickr, Dropzone, ApexCharts, FullCalendar
- **Garde-fou runtime accompli** : 3 E2E prouvent le **montage JS réel** (SVG ApexCharts, flatpickr ouvert, grille FullCalendar)
- **Réserve non utilisée** : US-019 (carte jsvectormap, 5 pts) → reste au backlog
