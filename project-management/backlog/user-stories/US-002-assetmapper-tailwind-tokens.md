# US-002 — Intégration AssetMapper + Tailwind CSS v4 et design tokens TailAdmin

**EPIC :** EPIC-001-fondations-socle-technique · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Must · **Sprint :** 1

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur** et **P-002 — Designer front-end**, je veux **que les styles Tailwind CSS v4 soient compilés et servis via AssetMapper avec les design tokens TailAdmin (couleurs, typographie, mode clair et mode sombre)**, afin de **disposer d'une base CSS cohérente et maintenable sur laquelle construire tous les composants du thème sans transpiler manuellement**.

## Conversation
Ce point est le plus risqué techniquement du Sprint 1 et doit faire l'objet d'un POC de bout en bout dès le premier jour. Tailwind CSS v4 abandonne le moteur JIT classique au profit d'un pipeline CSS natif basé sur `@import` et variables CSS : la commande de build change et l'intégration avec AssetMapper (qui ne gère pas nativement un step de compilation) nécessite soit un `bin/console asset-map:compile` adapté, soit un watcher externe minimal. La source de référence principale est `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/css/style.css` qui définit les directives Tailwind et les variables CSS du thème (couleurs `--color-brand-*`, typographie, espacements). Les design tokens dark mode utilisent la classe `.dark` sur `<html>` (stratégie retenue, cohérente avec US-005). L'`importmap` Symfony doit pointer vers le CSS compilé dans `public/assets/`. Le bundle expose ses assets via `AbstractBundle::getPath()` + `AssetPackage`. Un ADR doit être rédigé en phase Design pour documenter la décision finale d'intégration (watcher vs compile step, emplacements des sources CSS dans le bundle).

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Compilation et service des styles Tailwind v4 via AssetMapper
  Scenario: Les styles TailAdmin sont appliqués en mode développement
    Given la démo est démarrée en mode "dev"
    When le navigateur charge la page "/"
    Then la feuille de style compilée est chargée depuis "/assets/styles/app-*.css"
    And la couleur de fond du body correspond au token "--color-gray-50" défini dans "style.css"
    And la console navigateur ne contient aucune erreur 404 sur les assets
```
### Scénarios alternatifs
```gherkin
  Scenario: Les design tokens dark mode sont présents dans la feuille de style compilée
    Given le fichier "style.css" définit les variables sous ".dark"
    When la commande "bin/console asset-map:compile" est exécutée
    Then le fichier compilé contient les déclarations ".dark { --color-brand-* }"
    And le fichier compilé est présent dans "public/assets/"

  Scenario: Une classe Tailwind utilitaire arbitraire est disponible sans rebuild manuel
    Given la démo tourne en mode "dev" avec le watcher actif
    When le développeur ajoute la classe "bg-brand-500" dans un template Twig
    Then le rechargement de la page affiche l'arrière-plan de la couleur correspondante
    And aucune commande manuelle de rebuild n'a été nécessaire
```
### Scénarios d'erreur
```gherkin
  Scenario: Le fichier source CSS du bundle est introuvable
    Given le fichier "bundle/assets/css/style.css" a été supprimé par erreur
    When la commande "bin/console asset-map:compile" est exécutée
    Then la commande échoue avec un message d'erreur indiquant le fichier manquant
    And aucun fichier CSS partiel n'est écrit dans "public/assets/"

  Scenario: Conflit de version entre Tailwind v4 et un plugin v3 résiduel
    Given le "package.json" (ou "importmap") référence un plugin Tailwind v3
    When la compilation est lancée
    Then une erreur explicite indique l'incompatibilité de version
    And l'ADR documente la résolution retenue
```

## INVEST
- **Independent :** Dépend de US-001 (structure) mais n'est pas couplée aux US de composants UI.
- **Negotiable :** La stratégie d'intégration (watcher CLI, plugin Symfony, script npm minimaliste) est à arbitrer en Design ; l'US cadre le résultat, pas la méthode.
- **Valuable :** Sans CSS fonctionnel, aucun composant visuel ne peut être développé ni validé.
- **Estimable :** 8 points reflètent l'incertitude technique de l'intégration Tailwind v4 + AssetMapper, jamais documentée officiellement ensemble.
- **Small :** Limitée au pipeline CSS et aux tokens ; les composants UI sont dans d'autres US.
- **Testable :** Fichier compilé vérifiable, couleurs mesurables, erreurs console détectables.

## Dépendances
- **Dépend de :** US-001
- **Bloque :** US-004, US-005, US-006, US-007 (tout composant visuel)

## Definition of Done
Voir `project-management/definition-of-done.md` (fidélité TailAdmin, dark mode, responsive, a11y WCAG AA, PHPStan max, tests ≥80%, Stimulus, séparation bundle/démo, doc).
