# US-040 — Pages Task list : format liste + format Kanban

**EPIC :** EPIC-010-composants-affichage-pages · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Could · **Sprint :** sprint-011

## Carte (Card)
> En tant que **P-004 — utilisateur final de l'admin** (via l'intégration de **P-001**), je veux **deux vues de gestion de tâches — une liste filtrable et un tableau Kanban où je déplace les cartes entre colonnes**, afin de **suivre et organiser mon travail directement dans le back-office**.

## Conversation
Deux pages de démo (assemblage) portées de TailAdmin (réf. [/sales](https://demo.tailadmin.com/sales) comme vue **liste** de tâches et [/task-kanban](https://demo.tailadmin.com/task-kanban) comme vue **Kanban**). Réutilisent table, `tsf:Ui:Card`, badges, avatars, `tsf:Ui:Dropdown`, et si utile `tsf:Ui:ProgressBar`/`tsf:Ui:Tabs` (US-036).

- **Vue liste** : table de tâches (titre, assigné, priorité en badge, échéance, statut), avec en-têtes triables et filtres (statut/priorité) réutilisant les composants existants. Statique côté données (démo).
- **Vue Kanban** : colonnes par statut (À faire / En cours / Review / Terminé) avec cartes déplaçables. Le **glisser-déposer** utilise un **nouveau contrôleur Stimulus `tailsfadmin--kanban`** en **HTML5 Drag & Drop natif** (aucune dépendance externe, cohérent no-CDN ADR-004/006). Le déplacement met à jour le DOM (colonne, ordre) et un compteur par colonne ; accessibilité : alternative clavier (déplacer via menu/actions) et annonces `aria-live`. Si un besoin de tri riche émerge, une lib (ex. SortableJS) serait vendorée via `tailsfadmin:assets:install` — **hors scope** de cette US (YAGNI).

Livraison : 2 routes + templates dans la démo, entrées dans la galerie de pages. Clair/dark, responsive, WCAG AA.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Kanban de tâches
  Scenario: Déplacer une carte entre colonnes
    Given la page "task-kanban" affiche des colonnes À faire / En cours / Review / Terminé
    When l'utilisateur glisse une carte de "À faire" vers "En cours"
    Then la carte apparaît dans la colonne "En cours"
    And les compteurs des deux colonnes sont mis à jour en conséquence
```
### Scénarios alternatifs
```gherkin
  Scenario: Vue liste triable et filtrable
    Given la page liste de tâches (format "sales") est affichée
    When l'utilisateur trie par échéance et filtre sur la priorité "Haute"
    Then seules les tâches de priorité Haute sont affichées, triées par échéance

  Scenario: Alternative clavier au drag-and-drop
    Given une carte du Kanban a le focus
    When l'utilisateur ouvre le menu d'actions de la carte et choisit "Déplacer vers En cours"
    Then la carte est déplacée vers la colonne "En cours" sans souris
    And un message aria-live annonce le déplacement

  Scenario: Cohérence responsive du Kanban
    Given la page Kanban est affichée sur mobile
    When la largeur est réduite
    Then les colonnes deviennent défilables horizontalement sans casser la mise en page
```
### Scénarios d'erreur
```gherkin
  Scenario: Dépôt hors d'une colonne valide
    Given l'utilisateur glisse une carte
    When il la relâche en dehors de toute colonne
    Then la carte revient à sa position d'origine (aucune perte, aucune erreur JS)

  Scenario: Pas de dépendance JS tierce non vendorée
    Given la revue de code de la vue Kanban
    When le reviewer inspecte les imports du contrôleur
    Then le drag-and-drop repose sur l'API HTML5 native (aucun import de lib externe non vendorée)
```

## INVEST
- **Independent :** pages de démo autonomes, données factices ; réutilisent des composants livrés.
- **Negotiable :** ampleur du Kanban (persistance, tri fin) négociable ; consolidation avec l'item Kanban d'US-033 à arbitrer.
- **Valuable :** deux vues de suivi de tâches très demandées en back-office.
- **Estimable :** 2 pages + 1 contrôleur Stimulus (drag-and-drop natif + a11y) → 8 points.
- **Small :** borné à liste + Kanban en démo, sans backend réel.
- **Testable :** critères couvrant déplacement, tri/filtre, alternative clavier, responsive, dépôt invalide et absence de dépendance non vendorée.

## Dépendances
- **Dépend de :** US-036 (composants), table/badges/avatars/dropdown existants.
- **Chevauchement :** US-033 (EPIC-009) mentionne un Kanban → à consolider au raffinage pour éviter le doublon.
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.

---

## Notes
- Drag-and-drop en **HTML5 natif** via `tailsfadmin--kanban` : pas de CDN, pas de vendoring requis. Une lib de tri (SortableJS) resterait un choix ultérieur, vendoré via `tailsfadmin:assets:install`.
- Accessibilité : le glisser-déposer souris **doit** avoir une alternative clavier + annonces `aria-live`.
- Test e2e du déplacement (Panther) + tests fonctionnels de rendu des deux vues.
