# Backlog Index — tailsfadmin

> Dernière mise à jour : 2026-09-09 · **v1.0.0** (EPIC-001..007) + **v1.1.0 / Sprint 8** (EPIC-008) + **v1.2.0** (menu par permission, slots header — hors sprint tracké) + **v1.3.0 / Sprint 11** (EPIC-010) livrés · **reste** EPIC-009 (US-032→035, 26 pts — sprints 9-10)

---

## Résumé Global

| Type | 🔴 To Do | 🟢 Done | Total |
|------|----------|---------|-------|
| EPICs | 1 | 9 | 10 |
| User Stories | 4 (EPIC-009) | 35 | 39 |

**Livré :** ~197 pts (EPIC-001→008 + EPIC-010) · dernière version publiée sur Packagist : **v1.3.0** (Sprint 11 / EPIC-010) · **reste :** EPIC-009 — 26 pts (US-032→035, sprints 9-10)

---

## EPICs

| ID | Nom | Statut | Priorité | US | Points |
|----|-----|--------|----------|-----|--------|
| EPIC-001 | Fondations & socle technique | 🟢 Done | Must | 3 | 16 |
| EPIC-002 | Layout & navigation | 🟢 Done | Must | 4 | 26 |
| EPIC-003 | Bibliothèque de composants UI | 🟢 Done | Must | 6 | 24 |
| EPIC-004 | Formulaires & tables | 🟢 Done | Must | 4 | 21 |
| EPIC-005 | Data-viz & calendrier | 🟢 Done | Should | 3 | 21 |
| EPIC-006 | Pages applicatives & i18n | 🟢 Done | Should | 4 | 23 |
| EPIC-007 | Qualité, accessibilité & doc | 🟢 Done | Must | 2 | 13 |
| EPIC-008 | Distribution & consommabilité | 🟢 Done | Must | 5 | 26 |
| **EPIC-009** | **Bibliothèque de pages d'exemples** | 🟡 In Progress | Should | 4 | 26 |
| EPIC-010 | Composants d'affichage & pages d'exemple avancées | 🟢 Done | Could | 5 | 32 |

---

## User Stories — v1 (livrée)

Toutes les US US-001 → US-026 sont **🟢 Done** (bundle tailsfadmin 1.0.0).
US-019 (carte jsvectormap) livrée en Sprint 6 ; US-025/026 en Sprint 7.

| Sprint | US livrées |
|--------|-----------|
| 1 | US-001, US-002, US-003, US-004 |
| 2 | US-005, US-006, US-007 |
| 3 | US-008..US-012 |
| 4 | US-013, US-014, US-017 |
| 5 | US-015, US-016, US-018, US-020 |
| 6 | US-021, US-022, US-023, US-024, US-019 |
| 7 | US-025, US-026 |

---

## User Stories — v2 (planifiée)

### EPIC-008 — Distribution & consommabilité (Sprint 8) — 🟢 livré (v1.1.0)
| ID | Titre | Points | Priorité | Sprint | Statut |
|----|-------|--------|----------|--------|--------|
| US-027 | Recette d'assets : importmap fourni par le bundle | 8 | Must | 8 | 🟢 |
| US-028 | Thème CSS Tailwind distribuable + personnalisable | 5 | Must | 8 | 🟢 |
| US-029 | Flex recipe (config auto) | 5 | Should | 8 | 🟢 |
| US-030 | Test d'intégration app Symfony vierge (CI) | 5 | Must | 8 | 🟢 |
| US-031 | Publication Packagist | 3 | Must | 8 | 🟢 |

> Sprint 8 clôturé : bundle **v1.1.0** publié sur Packagist (`tailsfadmin/tailsfadmin-bundle`).
> US-029 : recette soumise à `symfony/recipes-contrib` (PR #2047) ; vérif *live* après merge upstream.

### EPIC-009 — Bibliothèque de pages d'exemples (Sprints 9-10) — 🟡 en cours
| ID | Titre | Points | Priorité | Sprint | Statut |
|----|-------|--------|----------|--------|--------|
| US-032 | Dashboards supplémentaires (Analytics, Marketing, CRM, SaaS) | 8 | Should | 9 | 🔴 |
| US-033 | Pages type (Settings, Pricing, Invoice, Chat, Files, Inbox) | 8 | Should | 9 | 🔴 |
| US-034 | Auth & utilitaires étendus (reset, 2FA, 500, maintenance…) | 5 | Could | 10 | 🔴 |
| US-035 | Scaffolding `make:tailsfadmin-page` | 5 | Could | 10 | 🔴 |

### EPIC-010 — Composants d'affichage & pages d'exemple avancées (Sprint 11) — 🟢 livré (v1.3.0)
| ID | Titre | Points | Priorité | Sprint | Statut |
|----|-------|--------|----------|--------|--------|
| US-036 | Composants d'affichage : Tabs, Progress bars, Ribbons | 8 | Could | 11 | 🟢 |
| US-037 | Layouts d'exemple supplémentaires (6 variantes) | 8 | Could | 11 | 🟢 |
| US-038 | Page « Form Layout » | 3 | Could | 11 | 🟢 |
| US-039 | Page « Integrations / API keys » | 5 | Could | 11 | 🟢 |
| US-040 | Pages Task list : liste + Kanban | 8 | Could | 11 | 🟢 |

---

## Légende statuts

🔴 To Do · 🟡 In Progress · ⏸️ Blocked · 🟢 Done
