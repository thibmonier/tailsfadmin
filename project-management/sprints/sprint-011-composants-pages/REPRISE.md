# 🔖 Reprise — Sprint 011 (à lire en premier après un /clear)

> Point de reprise du **2026-09-09**. Objectif : reprendre le sprint 11 sans
> reconstituer le contexte de mémoire.

## Où on en est

- **Bundle `tailsfadmin` public sur Packagist** : dernière version **v1.2.0**
  (`composer require tailsfadmin/tailsfadmin-bundle`). CI verte sur `main`.
- **Sprint 011 en cours** (EPIC-010 « Composants d'affichage & pages d'exemple »),
  **cible v1.3.0**, **32 pts engagés**.
- **US-036 (enabler composants) ✅ TERMINÉE** — livrée en 2 incréments, E2E Chrome vert.

## Avancement du sprint

| US | Pts | État |
|----|-----|------|
| **US-036** Tabs / ProgressBar / Ribbon | 8 | ✅ **terminée** |
| US-037 6 layouts d'exemple | 8 | 🔲 à faire |
| US-038 Page Form Layout | 3 | 🔲 à faire (rapide, autonome) |
| US-039 Page Integrations / API keys | 5 | 🔲 à faire (réutilise les **Tabs**) |
| US-040 Task list + Kanban | 8 | 🔲 à faire |

**8 / 32 pts livrés.** Ordre : US-036 (fait) → US-037/038/039/040 **parallélisables**.

## Ce qui est désormais disponible (livré par US-036)

- `tsf:Ui:ProgressBar` (clamp [0,100], `role=progressbar`), `tsf:Ui:Ribbon`
  (corner/rounded), `tsf:Ui:Tabs` (pattern ARIA) + contrôleur `tailsfadmin--tabs`.
- Vitrine : `/ui-kit#section-display` (démo). Doc : `docs/components.md`.

## Décisions clés (ne pas re-débattre)

1. **Dégradation gracieuse** pour les composants d'affichage (clamp / fallback,
   **pas** de `LogicException`) — cohérent avec `Badge`/`Button`. Validé.
2. **Kanban** : porté par **US-040**, spec = **TailAdmin `/task-kanban` (live)** ;
   retiré d'US-033 (EPIC-009).
3. **32 pts engagés** ; **US-037** = variable d'ajustement si retard mi-sprint.
4. **Sans CDN** (ADR-004/006) : toute interactivité = contrôleur Stimulus natif ;
   nouveaux contrôleurs déclarés dans **les deux** `package.json` + `demo/assets/controllers.json`
   (garde `AssetsWiringTest`, footgun US-019).
5. **Cible de version = v1.3.0** (v1.2.0 déjà prise par un autre lot : menu/header).

## Comment reprendre (workflow)

1. `git checkout main && git pull` (toujours partir de `main` à jour).
2. **Une branche par travail** : `git checkout -b feature/us-0XX-...`.
3. Développer en **TDD** (test → code). Détail des tâches : `tasks/US-0XX-tasks.md`.
4. **Gates avant push** (tout doit être vert) :
   - Bundle : `composer test` (PHPUnit), `composer phpstan` (niveau max), `composer cs`.
   - Twig : `cd demo && php bin/console lint:twig ...`.
   - Démo fonctionnel : `cd demo && php bin/phpunit --testsuite "Project Test Suite"`.
   - JS : Biome (via CI `front-lint` ; local capricieux).
   - E2E (Chrome) : validé par le job CI `e2e` (pas exécutable en sandbox local).
5. **PR → attendre CI verte (5 jobs) → merge → supprimer la branche.** Ne jamais
   merger en rouge (règle 09).

## Prochaine étape recommandée

- **US-038** (Form Layout, 3 pts) — rapide et autonome, ou
- **US-039** (API keys, 5 pts) — met en valeur les Tabs livrées (+ contrôleur `clipboard` à créer).

Lancer p. ex. : `/sprint:dev US-038` (ou implémenter directement selon `tasks/US-038-tasks.md`).

## En attente (externe — à surveiller, pas bloquant)

- **recipes-contrib PR #2047** : review par les mainteneurs Symfony.
- **Auto-update Packagist** : a priori fonctionnel (a pris v1.2.0) — **confirmer au prochain tag v1.3.0**.

## Repères utiles

- Décomposition complète : `tasks/README.md` + `tasks/US-0XX-tasks.md`.
- Planning / capacité / décisions : `sprint-planning.md`.
- Historique : **14 PRs mergées** cette session (jusqu'à #13 = US-036 incrément 2).
- Conventions composants : classe `src/Twig/Components/Ui/*.php` +
  template `templates/components/Ui/*.html.twig` + `#[AsTwigComponent('tsf:Ui:...')]`.
