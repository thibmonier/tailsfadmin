# Rétrospective — Sprint 005 (Data-viz & widgets riches)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-08 |
| Format | Starfish ⭐ |
| Contexte | Projet solo (développement assisté) |

## Directive Fondamentale

> « Peu importe ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait du mieux qu'il pouvait, compte tenu de ce qu'il savait à ce moment-là, de ses compétences et capacités, des ressources disponibles, et de la situation. » — Norman Kerth

## Rappel du sprint

- **Sprint Goal atteint : ✅** (4 libs JS intégrées, 24/24 pts — meilleure vélocité du projet).
- 157 tests + 5 E2E, PHPStan max 0, aucune régression.

---

## ⭐ Observations (Starfish)

### 🟢 CONTINUER
- **Petite US en tête de sprint pour dérisquer** : US-015 (flatpickr) a établi la recette de vendoring, réutilisée sans friction pour les 3 libs suivantes → vélocité record (24 pts).
- **Pattern wrapper uniforme** (connect/disconnect + destroy, values, MutationObserver dark) : cohérence et zéro fuite mémoire sur les 4 contrôleurs.
- **Garde-fou runtime enfin bouclé** : les E2E Panther prouvent le montage JS réel — ce que ni les tests fonctionnels ni un screenshot ne garantissent aussi bien.
- **Réutilisation** : calendrier ↔ modal (US-011) via l'API Stimulus, sans modification.

### 🟡 COMMENCER
- **Documenter la recette de vendoring** (importmap type:css, contournement stub FullCalendar) dans un ADR ou la doc — utile à tout consommateur du bundle.

### 🔴 ARRÊTER
- Rien de majeur.

### ⬆️ PLUS DE
- E2E Panther ciblés sur le comportement runtime critique (montage, ouverture) — bon ratio valeur/coût.

### ⬇️ MOINS DE
- Dépendance au screenshot : le montage prouvé par Panther > une capture (l'extension étant indisponible, ce fut sans impact réel cette fois).

---

## Analyse (5 pourquoi) — piège de résolution FullCalendar

**Constat** : `@fullcalendar/core` résolu en v7 (stub JSDelivr « should not be imported directly »).
1. Pourquoi ? → JSDelivr sert un stub pour l'import direct de `@fullcalendar/core` en v7.
2. Pourquoi ? → FullCalendar v6 attend un import depuis `@fullcalendar/core/index.js`, pas le package racine.
3. Pourquoi ? → Convention interne de packaging de FC6 (les plugins importent ainsi).
→ **Cause racine** : subtilité de packaging non documentée côté importmap. **Solution appliquée** : import depuis `/index.js` (comme les plugins). **Leçon** : quand un vendoring donne un stub, inspecter comment les sous-modules de la lib s'importent entre eux.

---

## 🎯 Actions Sprint 6 (SMART)

### Action 1 : ADR/doc « intégration d'une lib JS via importmap »
| Attribut | Valeur |
|----------|--------|
| Description | Documenter la recette : `importmap:require`, CSS type:css, wrapper Stimulus (connect/disconnect/destroy, dark MutationObserver), pièges (stubs) |
| Responsable | Dev |
| Deadline | Sprint 6 (ou US-026) |
| DoD | Doc/ADR référencé, réutilisable par un consommateur |
| Priorité | Moyenne · Statut : 🔵 À faire |

### Action 2 : Reconnecter l'extension Chrome pour la revue visuelle
| Attribut | Valeur |
|----------|--------|
| Description | Rétablir l'extension pour capturer le dashboard (charts + calendrier) clair/dark au Sprint 6 |
| Responsable | User + Orchestrateur |
| Deadline | Début Sprint 6 |
| DoD | Screenshots du dashboard assemblé |
| Priorité | Basse · Statut : 🔵 À faire (2e report) |

## Check-out

- **Ce que j'emporte** : une petite US bien choisie en ouverture de sprint peut dérisquer tout le reste ; et les E2E ciblés valent mieux qu'un screenshot pour prouver du runtime.
- **ROTI** : 5/5 — 100 % livré, meilleure vélocité, pattern d'intégration désormais rodé.

## Suivi actions Sprint 4
| Action | Statut |
|--------|--------|
| Fallback vérification de rendu (curl) | ✅ Appliqué (galerie vérifiée par curl + Panther) |
| Reconnecter l'extension Chrome | ⏳ Toujours en attente (2e report) |
