# US-018 — Graphiques ApexCharts via contrôleurs Stimulus

**EPIC :** EPIC-005-dataviz-calendrier · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur**, je veux **des contrôleurs Stimulus encapsulant ApexCharts (courbes, barres, graphiques de dashboard)** dont les données sont passées via Stimulus Values, afin de **inclure des graphiques interactifs et dark-mode aware dans n'importe quel template Twig sans écrire de JavaScript ApexCharts directement**.

## Conversation
Les sources de référence sont `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/js/components/charts/chart-01.js` (line chart), `chart-02.js` (bar chart), `chart-03.js` (graphique du dashboard), ainsi que les pages `src/line-chart.html` et `src/bar-chart.html`. Trois contrôleurs Stimulus sont créés dans `bundle/assets/controllers/` : `apexcharts-line-controller.js`, `apexcharts-bar-controller.js` et `apexcharts-area-controller.js`. Chaque contrôleur expose : **Values** `series` (JSON sérialisé des séries de données), `categories` (JSON, labels de l'axe X), `height` (number, défaut `350`), `colors` (JSON, tableau de couleurs HEX optionnel) ; **Target** `chartContainer` (l'élément DOM où ApexCharts monte le SVG). La détection du thème sombre est effectuée via `document.documentElement.classList.contains('dark')` à l'initialisation et via `MutationObserver` pour les changements en temps réel ; les couleurs de texte, de grille et de fond sont adaptées. ApexCharts est chargé via `importmap` (package `apexcharts`). Les composants Twig `<twig:Chart:Line />`, `<twig:Chart:Bar />`, `<twig:Chart:Area />` génèrent le HTML avec les `data-*-value` appropriés. L'app de démo expose `/graphiques/line`, `/graphiques/bar` avec des données factices JSON.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Graphique ApexCharts line chart via Stimulus
  Scenario: Rendu d'un graphique en courbes avec données
    Given le composant "<twig:Chart:Line :series='[[10,20,30]]' :categories='["Jan","Fév","Mar"]' />"
    When la page est rendue dans la démo
    Then ApexCharts monte un graphique SVG dans l'élément "chartContainer"
    And la courbe trace les valeurs 10, 20, 30 sur les mois Jan, Fév, Mar
    And le graphique est interactif (tooltip au survol)
```
### Scénarios alternatifs
```gherkin
  Scenario: Graphique en barres avec plusieurs séries
    Given le composant "<twig:Chart:Bar>" avec deux séries de 6 valeurs chacune
    When la page est rendue
    Then ApexCharts affiche deux groupes de barres côte à côte par catégorie
    And la légende distingue les deux séries par couleur

  Scenario: Adaptation automatique au thème sombre
    Given un graphique ApexCharts est affiché en thème clair
    When l'utilisateur bascule vers le thème sombre (classe "dark" ajoutée à <html>)
    Then ApexCharts redessine le graphique avec fond foncé, texte clair et grille adaptée
    And la transition est sans rechargement de page

  Scenario: Mise à jour des données via Stimulus Values
    Given un graphique line chart est affiché
    When le contrôleur reçoit une nouvelle valeur "series" via JavaScript
    Then ApexCharts met à jour le graphique via "chart.updateSeries()" sans re-rendu DOM complet
    And l'animation de mise à jour est visible

  Scenario: Graphique du dashboard (chart-03) intégré dans la page d'accueil
    Given la page "/dashboard" est chargée
    Then le graphique de synthèse (area/line combo) est rendu dans la section statistiques
    And sa hauteur correspond à la valeur Value "height" déclarée
```
### Scénarios d'erreur
```gherkin
  Scenario: Données JSON malformées dans la Value "series"
    Given la Value "series" contient du JSON invalide (ex. "[10, 20, x]")
    When le contrôleur Stimulus s'initialise
    Then ApexCharts n'est pas instancié
    And une erreur explicite est consignée dans la console : "ApexCharts : données 'series' invalides"
    And un état vide/placeholder est affiché dans "chartContainer"

  Scenario: ApexCharts non chargé (erreur réseau importmap)
    Given la lib ApexCharts ne peut pas être chargée
    When la page est rendue
    Then "chartContainer" affiche un message "Graphique indisponible"
    And l'erreur de chargement est consignée dans la console sans bloquer le reste de la page
```

## INVEST
- **Independent :** Les contrôleurs ApexCharts sont indépendants des composants de formulaire et de navigation.
- **Negotiable :** Les trois types de graphiques peuvent être livrés en deux itérations (line + bar d'abord, area ensuite) si nécessaire.
- **Valuable :** Les graphiques constituent la fonctionnalité de dataviz la plus visible d'un dashboard ; ils sont cités dans le MMF de EPIC-005.
- **Estimable :** 8 points pour trois contrôleurs Stimulus, trois composants Twig et la gestion du dark mode dynamique.
- **Small :** Limité à ApexCharts ; la carte vectorielle (jsvectormap) et le calendrier (FullCalendar) sont dans US-019 et US-020.
- **Testable :** Test vérifiant la présence du SVG ApexCharts dans le DOM après montage ; test snapshot des composants Twig.

## Dépendances
- **Dépend de :** US-002 (importmap et infrastructure Stimulus opérationnelle)
- **Bloque :** US-021 (page dashboard complète)

## Definition of Done
Voir `project-management/definition-of-done.md`.
