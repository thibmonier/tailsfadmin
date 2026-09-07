# Rétrospective — Sprint 001 (Walking Skeleton)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-07 |
| Format | Starfish ⭐ |
| Facilitateur | Scrum Master (assisté) |
| Contexte | Projet solo (dev unique, développement assisté) |

## Directive Fondamentale

> « Peu importe ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait du mieux qu'il pouvait, compte tenu de ce qu'il savait à ce moment-là, de ses compétences et capacités, des ressources disponibles, et de la situation. » — Norman Kerth

## Rappel du sprint

- **Sprint Goal atteint : ✅** (Walking Skeleton complet, 21/21 pts).
- 15/15 tests verts, PHPStan max 0 erreur, risque n°1 levé.

---

## ⭐ Observations (Starfish)

### 🟢 CONTINUER (ce qui fonctionne bien)
- **POC-first sur le risque n°1** : attaquer Tailwind v4 ⇄ AssetMapper dès le Sprint 1 a dérisqué tout le backlog.
- **ADR avant code** : les décisions (structure bundle, packaging Stimulus, i18n) étaient tranchées avant d'implémenter → peu d'allers-retours.
- **TDD RED→GREEN** systématique sur les parties testables.
- **Vérification indépendante** des livrables (re-run des tests/PHPStan/HTTP réel), au lieu de croire les rapports sur parole.
- **Parallélisation sur fichiers disjoints** (US-002 assets / US-003 Docker) sans conflit.
- **Épinglage des versions** (binaire Tailwind `v4.3.3`, `binary_version`) pour la reproductibilité.

### 🟡 COMMENCER (nouvelles idées à essayer)
- Un **script `composer test`** dans la démo enchaînant `tailwind:build → asset-map:compile → phpunit` (évite le footgun du hash stale).
- **Vérifier la compatibilité des versions** d'un couple d'outils avant de l'inscrire dans la stack (cas « Pest 4 / PHPUnit 12 »).
- **Tester la consommation du bundle sur une app tierce** (pas seulement la démo interne) pour valider la réutilisabilité réelle.

### 🔴 ARRÊTER (ce qui ne fonctionne pas)
- **Lancer `tailwind:build` sans `asset-map:compile`** ensuite → 404 CSS transitoire (hash désynchronisé).
- **Inscrire des couples de versions non vérifiés** dans la doc de stack (Pest 4 + PHPUnit 12 sont incompatibles).

### ⬆️ PLUS DE
- Vérification bout-en-bout sur le **runtime réel** (Docker/FrankenPHP) et pas seulement les tests unitaires.
- Consignation des **enseignements d'implémentation dans les ADR** (fait pour ADR-001/003).

### ⬇️ MOINS DE
- Hypothèses sur l'**ordre des commandes** manuelles (préférer des scripts qui encapsulent la séquence correcte).

---

## Thèmes & analyse (5 pourquoi)

### Thème 1 : Footgun assets (hash stale)
**Problème** : un test a échoué de façon transitoire après un `tailwind:build` sans recompilation.
1. Pourquoi ? → Le hash de contenu du CSS a changé mais `public/assets/` n'a pas été régénéré.
2. Pourquoi ? → `tailwind:build` et `asset-map:compile` sont deux étapes séparées.
3. Pourquoi ? → Aucune commande unique n'encapsule la séquence correcte.
→ **Cause racine** : absence de tâche « test » canonique. **Solution** : script `composer test`.

### Thème 2 : Contradiction de stack (Pest/PHPUnit)
**Problème** : la stack cible « Pest 4 / PHPUnit 12 » est intrinsèquement incompatible.
→ **Cause racine** : couple de versions non vérifié à l'écriture de la doc. **Solution** : acter PHPUnit 12 seul et corriger la doc.

---

## 🎯 Actions Sprint 2 (SMART)

### Action 1 : Script `composer test` (fin du footgun assets)
| Attribut | Valeur |
|----------|--------|
| Description | Ajouter à `demo/composer.json` un script `test` = `tailwind:build && asset-map:compile && phpunit` |
| Responsable | Dev |
| Deadline | Sprint 2 (rattaché à US-026) |
| DoD | `composer test` reproductible et vert depuis un checkout propre |
| Priorité | Haute |
| Statut | 🔵 À faire |

### Action 2 : Corriger la contradiction de stack Pest/PHPUnit
| Attribut | Valeur |
|----------|--------|
| Description | Mettre à jour la doc projet (`.claude/CLAUDE.md`/PRD) : PHPUnit 12 retenu, Pest écarté (incompatible) |
| Responsable | Dev |
| Deadline | Sprint 2 |
| DoD | Doc cohérente, plus de mention « Pest 4 » comme requis |
| Priorité | Moyenne |
| Statut | 🔵 À faire |

### Action 3 : Documenter le caveat de réutilisabilité (asset_mapper)
| Attribut | Valeur |
|----------|--------|
| Description | Guide d'installation : l'app hôte ajoute le chemin des contrôleurs du bundle dans `asset_mapper.yaml` ; tester sur une app tierce |
| Responsable | Dev |
| Deadline | Sprint 2/3 (US-026) |
| DoD | Section « Installation » validée sur une app Symfony vierge |
| Priorité | Moyenne |
| Statut | 🔵 À faire |

## Check-out

- **Ce que j'emporte** : le POC-first et l'ADR-avant-code ont payé ; le seul vrai piège fut opérationnel (ordre des commandes assets), pas architectural.
- **ROTI** : 5/5 — le sprint a validé la faisabilité de tout le projet.

## Suivi
Ces actions sont reprises dans le suivi (`workflow-status.yaml`) et rattachées à US-026 le cas échéant.
