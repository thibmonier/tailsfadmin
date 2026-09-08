# Rétrospective — Sprint 007 (Qualité, accessibilité & livrabilité) · Clôture projet

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-08 |
| Format | Starfish ⭐ + rétro de clôture projet |
| Contexte | Projet solo (développement assisté) |

## Directive Fondamentale

> « Peu importe ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait du mieux qu'il pouvait, compte tenu de ce qu'il savait à ce moment-là, de ses compétences et capacités, des ressources disponibles, et de la situation. » — Norman Kerth

## Rappel du sprint

- **Sprint Goal atteint : ✅** — US-025 (accessibilité, 0 violation axe A/AA) +
  US-026 (CI + doc + livrabilité) = **13/13 pts**.
- Bundle **tailsfadmin 1.0.0** : dernier sprint, backlog vidé.

---

## ⭐ Observations (Starfish)

### 🟢 CONTINUER
- **Audit automatisé comme oracle** : axe-core injecté en Panther a rendu
  l'accessibilité binaire et reproductible — bien plus fiable qu'une revue manuelle.
- **Corriger dans les composants du bundle** : contrastes/ARIA réglés à la source →
  tout consommateur en bénéficie, cohérence garantie.
- **Garde de régression pour chaque défaut** : la garde visuelle dark verrouille la
  dette du Sprint 6 ; la garde focus-trap verrouille le bug d'US-011.

### 🟡 COMMENCER
- **Mesurer la couverture tôt** : l'absence de pcov/xdebug local a empêché de
  vérifier le seuil 80 % — prévoir un driver de couverture dès le setup.

### 🔴 ARRÊTER
- **Débugger un rendu sans neutraliser le timing** : beaucoup de temps perdu sur des
  couleurs axe « incohérentes » qui n'étaient qu'un défaut de recalcul de styles
  après le toggle de thème (reflow + attente manquants).

### ⬆️ PLUS DE
- **Aller à la source de vérité rapidement** : lire le CSS/DOM réel (et non empiler
  les mesures) a débloqué la dette dark-mode ET le faux problème de contraste.

### ⬇️ MOINS DE
- **Suppositions sur le rendu** : le navigateur réel et les computed styles priment
  toujours sur le raisonnement de spécificité CSS a priori.

---

## Analyse (5 pourquoi) — « couleurs de contraste incohérentes »

**Constat** : axe reportait des couleurs (#5f6b7c, #989da4…) ne correspondant à aucun token.
1. Pourquoi ? → axe lisait la couleur AVANT que le style ne soit recalculé.
2. Pourquoi ? → le thème était basculé via `classList.add('dark')` juste avant `axe.run`.
3. Pourquoi ? → aucun reflow/attente n'était forcé entre le toggle et l'audit.
4. Pourquoi ? → l'API Panther `executeScript` est synchrone mais le style se recalcule au repaint suivant.
→ **Cause racine** : condition de course rendu/audit. **Solution** : `void offsetHeight` + `usleep` après chaque changement de thème. **Leçon** : tout audit de rendu doit attendre la stabilisation des styles.

---

## Bilan projet (7 sprints)

| Métrique | Valeur |
|----------|--------|
| Sprints | 7 |
| User Stories | 24 livrées (US-001..026, hors doublons) + US-019 |
| Points livrés | ~139 (S1 21 · S2 21 · S3 19 · S4 18 · S5 24 · S6 23 · S7 13) |
| Tests | 180 fonctionnels + 14 E2E · PHPStan max 0 · cs-fixer 0 · biome 0 |
| Accessibilité | WCAG 2.2 AA, 0 violation axe (clair + dark) |
| Livrable | Bundle réutilisable 1.0.0 + app de démo fidèle à TailAdmin |

### Ce qui a le mieux marché sur le projet
- **Walking skeleton en S1** puis assemblage incrémental : chaque sprint capitalisait sur le précédent.
- **Pattern wrapper Stimulus uniforme** (ADR-004) : 6 libs JS intégrées sans friction.
- **Revue visuelle + console + E2E** comme triple filet : a débusqué des bugs invisibles aux tests DOM (focus trap, dark-mode, dropdowns muets).

### Dettes / suivis résiduels
- Couverture 80 % à confirmer au premier run CI.
- Traduction du CONTENU des pages (au-delà du chrome) si besoin multilingue complet.

## 🎯 Actions post-projet (release)
1. PR `feature/...` → `main`, CI verte (couverture, E2E+axe, biome).
2. Tag **v1.0.0** (CHANGELOG et `composer.json` prêts).
3. Publication Packagist (optionnel) + retrait du champ `version` de composer.json si publié.

## Check-out

- **ROTI projet** : 5/5 — bundle complet, accessible, documenté, testé, livrable.
- **Ce que j'emporte** : l'automatisation (tests, axe, CI) transforme des critères
  subjectifs en garanties ; et la source de vérité (navigateur, CSS réel) tranche
  toujours plus vite que la théorie.

## Suivi actions Sprint 6

| Action | Statut |
|--------|--------|
| ADR « intégration lib JS » | ✅ Livré (ADR-006) |
| Garde visuelle dark (anti-régression) | ✅ Livré (AccessibilityE2ETest) |
| Doc gotchas composants | ✅ Livré (docs/components.md) |
