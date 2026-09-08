# Rétrospective — Sprint 006 (Pages applicatives & internationalisation)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-08 |
| Format | Starfish ⭐ |
| Contexte | Projet solo (développement assisté) |

## Directive Fondamentale

> « Peu importe ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait du mieux qu'il pouvait, compte tenu de ce qu'il savait à ce moment-là, de ses compétences et capacités, des ressources disponibles, et de la situation. » — Norman Kerth

## Rappel du sprint

- **Sprint Goal atteint : ✅** — 4 US (US-021 dashboard, US-022 profil, US-023 auth/utilitaires, US-024 i18n+RTL) = **23/23 pts**.
- **Extras livrés à la suite** : `fix(modal)` focus trap, `feat(button)` prop `block`, `fix(dark-mode)` inversion des gris (dette résolue), **US-019** (carte jsvectormap).
- **Qualité** : démo **180/180** · **E2E Panther 6/6** · PHPStan max 0 · php-cs-fixer 0 · revues visuelles clair+dark systématiques (dont **dark×RTL**), 0 erreur console.

---

## ⭐ Observations (Starfish)

### 🟢 CONTINUER
- **Revue console navigateur = filet le plus fiable** : elle a révélé le bug a11y du focus trap modal (`this.constructor.#FOCUSABLE`), **invisible aux tests DOM ET E2E** (la modale s'affichait quand même). Confirme la leçon S5 : lire la console à l'ouverture de chaque composant JS.
- **Vérifier la source de référence avant un fix d'architecture** : pour la dette dark-mode, lire `style.css` de TailAdmin a été décisif — il ne redéfinit AUCUN token gris en dark, ce qui a tranché « décision A » sans tâtonner.
- **Assemblage sur une bibliothèque mûre** : le dashboard (US-021) et le profil (US-022) se sont composés vite à partir des composants existants — la bibliothèque construite aux sprints 1-5 a payé.
- **Sous-agents d'exploration + revérification systématique** : cartographier signatures/sources via sous-agent, puis relire soi-même, a évité les erreurs d'API composant.

### 🟡 COMMENCER
- **Garde de régression dès qu'un bug est trouvé** : fait pour le focus trap (assertion E2E « focus piégé dans le dialog »). À généraliser à tout défaut découvert.
- **Auditer les autres angles morts « inversion de tokens »** : un test/garde visuelle dark ciblé (bouton secondary, input, panel modal) éviterait une régression de la dette dark-mode.

### 🔴 ARRÊTER
- **Empiler les mesures contradictoires sans lire la source** : sur la dette dark-mode, plusieurs `getComputedStyle` se contredisaient ; c'est la lecture du CSS source qui a levé l'ambiguïté. Aller à la source de vérité plus tôt.

### ⬆️ PLUS DE
- **Commits atomiques par préoccupation** : `fix(modal)` séparé de `feat(profile)`, `feat(button)` séparé de `feat(auth)` — historique lisible.
- **Documenter la cause racine AVANT de coder le fix** (fait via `docs:` pour la dette dark-mode) — clarifie la décision et sert de trace.

### ⬇️ MOINS DE
- **Correctifs partiels d'un problème systémique** : tentation de ne corriger que le bouton secondary ; la bonne voie était le fix global (décision A) après compréhension complète.

---

## Analyse (5 pourquoi) — surfaces claires en dark mode

**Constat** : inputs, dropdowns, panel de modale, bouton secondary rendaient CLAIR en dark.
1. Pourquoi ? → `dark:bg-gray-{800,900}` donnait une couleur claire en dark.
2. Pourquoi ? → sous `.dark`, `--color-gray-900` valait `#eaecef` (clair).
3. Pourquoi ? → le bloc `.dark` de `app.css` **inversait toute l'échelle de gris**.
4. Pourquoi ? → inversion ajoutée au portage Sprint 2 (intention « gris sémantiques auto-inversés »).
5. Pourquoi ? → mais les composants portés de TailAdmin utilisent des variantes `dark:` explicites attendant un gray-900 **sombre** — incompatible avec l'inversion.
→ **Cause racine** : divergence entre la stratégie de tokens (inversion) et l'usage des composants (variantes explicites). **Solution** : retirer l'inversion (approche TailAdmin, décision A). **Leçon** : une customisation de tokens doit être cohérente avec la façon dont TOUS les composants les consomment.

## Autre piège notable

- **StimulusBundle & manifeste** : ajouter un contrôleur au bundle exige de le déclarer dans `assets/package.json` (`symfony.controllers`), pas seulement `controllers.json` — sinon « controller does not exist in the package ». Documenté pour US-026.

---

## 🎯 Actions Sprint 7 (SMART)

### Action 1 : ADR « intégration d'une lib JS via importmap » (report S5 → S7)
| Attribut | Valeur |
|----------|--------|
| Description | Documenter la recette complète : `importmap:require` (+ type:css), wrapper Stimulus (connect/disconnect/destroy, dark), **déclaration dans `assets/package.json`**, pièges (stubs, manifeste) |
| Responsable | Dev |
| Deadline | Sprint 7 (US-026) |
| DoD | ADR/doc référencé, réutilisable par un consommateur du bundle |
| Priorité | Moyenne · Statut : 🔵 À faire |

### Action 2 : Garde visuelle dark ciblée (anti-régression dette)
| Attribut | Valeur |
|----------|--------|
| Description | Test/garde vérifiant qu'un input / bouton secondary / panel modal reste sombre en dark (computed bg foncé) |
| Responsable | Dev |
| Deadline | Sprint 7 (US-025 a11y ou US-026) |
| DoD | Garde en place, échoue si l'inversion réapparaît |
| Priorité | Moyenne · Statut : 🔵 À faire |

### Action 3 : Gotchas composants → doc contributeur
| Attribut | Valeur |
|----------|--------|
| Description | Consigner : `tsf:Ui:Button` ne propage pas `{{ attributes }}` ; `_self.macro()` ne traverse pas un slot de composant (→ `include`) ; SVG en prop via `:iconStart="var"` |
| Responsable | Dev |
| Deadline | Sprint 7 (US-026) |
| DoD | Section « pièges » dans la doc composants |
| Priorité | Basse · Statut : 🔵 À faire |

## Check-out

- **Ce que j'emporte** : la console (et la source de vérité) attrapent ce que les tests verts cachent ; comprendre AVANT de corriger un problème systémique évite les patchs partiels incohérents.
- **ROTI** : 5/5 — Sprint Goal + dette résolue + US-019 bonus, sans régression.

## Suivi actions Sprint 5

| Action | Statut |
|--------|--------|
| Action 1 — ADR « intégration lib JS » | ⏳ Reportée S7/US-026 (matière enrichie : jsvectormap + manifeste package.json) |
| Action 2 — Reconnecter l'extension Chrome | ✅ **Résolue** — extension opérationnelle, revues visuelles clair/dark/RTL capturées tout le sprint |
