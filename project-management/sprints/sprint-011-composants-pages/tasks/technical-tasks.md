# Tâches Techniques Transverses — Sprint 011

## Infrastructure / scaffolding

### T-TECH-01 : Scaffolding du sprint + task-board
- **Type** : [OPS]
- **Estimation** : 0.5h
- **Description** : structure `sprint-011-composants-pages/` (goal, dependencies, tasks/, board) — **fait** dans cette décomposition.
- **Critères** : arborescence conforme au standard SCRUM du projet.

## Qualité assets

### T-TECH-02 : Garde synchro package.json (nouveaux contrôleurs)
- **Type** : [FE-WEB]
- **Estimation** : 1h
- **Dépend de** : T-036-07 (`tabs`), T-039-03 (`clipboard`), T-040-03 (`kanban`)
- **Description** : s'assurer que les 3 nouveaux contrôleurs Stimulus sont déclarés
  **à l'identique** dans `package.json` (racine) **et** `assets/package.json`, sous peine
  du footgun US-019 (« controller does not exist in the package »).
- **Fichiers** : `package.json`, `assets/package.json`, `tests/Functional/AssetsWiringTest.php`.
- **Critères** :
  - [ ] `AssetsWiringTest` passe (les deux package.json listent les mêmes contrôleurs).
  - [ ] Chaque nouveau contrôleur monte réellement dans la démo (job e2e vert).

## Note — pas de vendoring requis
Aucune dépendance JS tierce n'est introduite dans ce sprint : Tabs, clipboard et Kanban
utilisent des API natives (pas de CDN, pas de `tailsfadmin:assets:install`). Si un besoin
de tri riche émergeait (ex. SortableJS pour US-040), il ferait l'objet d'une tâche
[BE]/[FE-WEB] dédiée avec vendoring via `tailsfadmin:assets:install` — **hors périmètre**.

## Résumé
| ID | Type | Tâche | Heures |
|----|------|-------|--------|
| T-TECH-01 | [OPS] | Scaffolding sprint | 0.5h |
| T-TECH-02 | [FE-WEB] | Garde synchro package.json | 1h |
| **TOTAL** | | | **1.5h** |
