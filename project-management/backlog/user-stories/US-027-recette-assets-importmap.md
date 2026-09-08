# US-027 — Recette d'assets : entrées importmap fournies par le bundle

**EPIC :** EPIC-008-distribution-consommabilite · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Must · **Sprint :** Sprint 8

## Carte (Card)
> En tant que **P-003 — Mainteneur/consommateur du bundle**, je veux **que le bundle déclare lui-même les bibliothèques JS dont ses contrôleurs Stimulus dépendent (ApexCharts, jsvectormap, flatpickr, Dropzone, FullCalendar) dans l'importmap de l'app hôte**, afin de **ne PAS avoir à recopier `demo/importmap.php` à la main lors de l'intégration dans un projet tiers (hottwos)**.

## Conversation
Aujourd'hui les entrées importmap vivent dans `demo/importmap.php` (hors bundle) ;
le `prepend()` du bundle ne gère que le namespace Twig, le path AssetMapper des
contrôleurs et les traductions. Il faut que le bundle, via `prepend()` (ou une
recette), ajoute à l'importmap de l'hôte les libs JS requises (avec versions
pinées) et déclenche leur vendoring — sans CDN (ADR-004/ADR-006). Deux pistes :
(a) `prependExtensionConfig` importmap si l'API le permet, (b) fournir un
`importmap.php` partiel documenté + commande d'aide. Le contrat : après install,
`bin/console importmap:install` (ou équivalent) rend les libs disponibles offline.
Les contrôleurs qui n'ont pas de dépendance externe (theme, sidebar, modal…)
continuent de fonctionner via le path AssetMapper déjà exposé.

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Le bundle fournit ses dépendances JS
  Scenario: Une app vierge obtient les libs sans recopier l'importmap
    Given une app Symfony neuve avec AssetMapper et le bundle installé
    When le développeur exécute la commande d'installation des assets du bundle
    Then l'importmap de l'app contient les entrées des libs JS requises (versions pinées)
    And les fichiers sont vendorés localement (aucun CDN au runtime)
    And un composant tsf:Chart:Line monte réellement son SVG dans le navigateur

  Scenario: Contrôleur sans dépendance externe
    Given le bundle est installé
    When une page utilise tsf:Ui:Modal (aucune lib tierce)
    Then la modale fonctionne sans qu'aucune entrée importmap supplémentaire soit nécessaire

  Scenario: Absence de la lib requise (erreur)
    Given une lib JS requise n'a pas été vendorée
    When la page charge un composant qui en dépend
    Then une erreur explicite est visible (console) indiquant la commande à exécuter
```

## INVEST
- **Independent** : traite le câblage assets, indépendamment des pages.
- **Negotiable** : mécanisme (prepend vs recipe vs commande) à arbitrer techniquement.
- **Valuable** : supprime le principal frein à l'adoption (copier-coller d'importmap).
- **Estimable** : audit importmap + prepend/commande + vendoring + test — 8 pts.
- **Small** : périmètre = distribution des libs JS, pas les pages.
- **Testable** : test d'intégration app vierge + montage JS (Panther).

## Dépendances
- **Dépend de :** bundle v1.0.0 (contrôleurs + composants Chart existants).
- **Bloque :** US-029 (recipe), US-030 (test app vierge), US-031 (Packagist).

## Definition of Done
Voir `project-management/definition-of-done.md`.
