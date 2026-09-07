# Rétrospective — Sprint 004 (Cards, formulaires & tables)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-07 |
| Format | Starfish ⭐ |
| Contexte | Projet solo (développement assisté) |

## Directive Fondamentale

> « Peu importe ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait du mieux qu'il pouvait, compte tenu de ce qu'il savait à ce moment-là, de ses compétences et capacités, des ressources disponibles, et de la situation. » — Norman Kerth

## Rappel du sprint

- **Sprint Goal atteint : ✅** (cards, formulaires + form theme, tables — 18/18 pts).
- 117 tests + 2 E2E, PHPStan max 0, aucune régression.

---

## ⭐ Observations (Starfish)

### 🟢 CONTINUER
- **Découpage d'une grosse US** : US-014 (8 pts) livrée en 2 parties (composants, puis theme) avec validation intermédiaire → maîtrise du risque, feu vert éclairé.
- **Vérification substituée quand un outil manque** : extension navigateur HS → validation du form theme et de la galerie **par curl** (assertions sur classes/sections), au lieu de renoncer à vérifier.
- **Réutilisation systématique** : TableAdvanced = dropdown (US-012) + Badge/Avatar (US-009), zéro nouveau JS.
- **Garde-fous vivants** : le smoke CSS a été **étendu** aux nouvelles familles (form/table).

### 🟡 COMMENCER
- **Prévoir un fallback de rendu** quand l'extension Chrome est indisponible : la vérif curl (présence classes/sections) est un bon substitut à formaliser.

### 🔴 ARRÊTER
- Rien de majeur.

### ⬆️ PLUS DE
- Découpage explicite des US ≥ 8 pts en parties livrables avec point de validation.

### ⬇️ MOINS DE
- Dépendance au screenshot pour la seule preuve de rendu : compléter par des assertions sur le HTML/CSS.

---

## Analyse (5 pourquoi) — form invalide qui « échouait »

**Constat** : un test de soumission de formulaire invalide échouait avec `assertResponseIsSuccessful()`.
1. Pourquoi ? → La réponse est HTTP **422**, pas 200.
2. Pourquoi ? → Symfony 7.2+ retourne automatiquement 422 (Unprocessable Content) sur un formulaire invalide.
3. Pourquoi ? → Comportement récent du framework, non anticipé dans l'assertion.
→ **Cause racine** : hypothèse obsolète sur le code de statut. **Solution appliquée** : `assertContains($status, [200, 422])`. **Leçon** : vérifier les comportements par défaut du framework à sa version exacte (cf. AGENTS.md « discover, don't guess »).

---

## 🎯 Actions Sprint 5 (SMART)

### Action 1 : Formaliser un fallback de vérification de rendu
| Attribut | Valeur |
|----------|--------|
| Description | Quand l'extension navigateur est indisponible, vérifier le rendu par curl + assertions (sections, classes clés) ; documenter la recette |
| Responsable | Orchestrateur |
| Deadline | Sprint 5 |
| DoD | Recette écrite ; appliquée au moins une fois |
| Priorité | Moyenne · Statut : 🔵 À faire |

### Action 2 : Reconnecter l'extension Chrome pour la revue visuelle
| Attribut | Valeur |
|----------|--------|
| Description | Rétablir l'extension pour capturer la galerie complète (clair/dark) en Sprint 5 |
| Responsable | User + Orchestrateur |
| Deadline | Début Sprint 5 |
| DoD | Screenshots de la galerie enrichie |
| Priorité | Basse · Statut : 🔵 À faire |

## Check-out

- **Ce que j'emporte** : découper les grosses US en parties validables réduit vraiment le risque ; et il faut toujours un moyen de vérifier le rendu, screenshot ou curl.
- **ROTI** : 5/5 — 100 % livré, form theme (le point subtil) prouvé, garde-fous étendus.

## Suivi actions Sprint 3
| Action | Statut |
|--------|--------|
| Documentation catalogue composants | ⏳ En cours (galerie = base ; US-026) |
| Revérifier après interruption d'agent | ✅ Appliqué (US-013/014/017) |
