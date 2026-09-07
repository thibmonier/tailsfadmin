# US-019 — Carte vectorielle mondiale avec jsvectormap

**EPIC :** EPIC-005-dataviz-calendrier · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Could · **Sprint :** backlog

## Carte (Card)
> En tant que **P-004 — Utilisateur final (admin)**, je veux **visualiser une carte du monde vectorielle avec des marqueurs de données**, afin de **identifier rapidement les zones géographiques d'activité dans le dashboard sans quitter l'interface**.

## Conversation
Les sources de référence sont `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/js/components/map-01.js` (initialisation jsvectormap avec la carte `world_mill` et les marqueurs) et `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/map-01.html` (HTML conteneur). Le contrôleur Stimulus `vectormap-controller.js` est placé dans `bundle/assets/controllers/` et référencé dans `importmap`. Il expose les **Values** : `map` (string, défaut `"world_mill"`), `markers` (JSON, tableau d'objets `{name, latLng: [lat, lng]}`), `backgroundColor` (string, défaut `"transparent"`), `regionColor` (string, couleur de remplissage des pays, défaut `"#C9D0D8"`). La **Target** `mapContainer` désigne le div où jsvectormap monte le SVG. Le contrôleur adapte les couleurs en fonction du thème (dark/light) via `MutationObserver` sur `document.documentElement`. La lib `jsvectormap` et la carte `@jsvectormap/maps/world` sont chargées via `importmap`. Le composant Twig `<twig:Chart:VectorMap />` génère le div avec les `data-*-value`. L'app de démo expose `/graphiques/carte` avec des marqueurs de pays prédéfinis.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Carte vectorielle jsvectormap via Stimulus
  Scenario: Rendu de la carte mondiale avec marqueurs
    Given le composant "<twig:Chart:VectorMap :markers='[{"name":"France","latLng":[46,2]}]' />"
    When la page "/graphiques/carte" est chargée
    Then jsvectormap monte un SVG de la carte mondiale dans "mapContainer"
    And le marqueur "France" est visible à la position géographique correcte
    And un tooltip affiche "France" au survol du marqueur
```
### Scénarios alternatifs
```gherkin
  Scenario: Carte en thème sombre
    Given le thème sombre est actif
    When la carte est rendue
    Then les régions affichent une couleur adaptée au fond sombre
    And les marqueurs restent lisibles (contraste suffisant WCAG AA)

  Scenario: Carte sans marqueurs (affichage seul)
    Given le composant est utilisé sans Value "markers"
    When la page est rendue
    Then la carte mondiale s'affiche sans marqueur
    And aucune erreur JavaScript n'est consignée dans la console

  Scenario: Zoom et déplacement sur la carte
    Given la carte est rendue
    When l'utilisateur utilise la molette de la souris sur la carte
    Then la carte effectue un zoom centré sur le curseur
    When l'utilisateur fait glisser la carte
    Then la vue se déplace en conséquence
```
### Scénarios d'erreur
```gherkin
  Scenario: JSON des marqueurs malformé
    Given la Value "markers" contient du JSON invalide
    When le contrôleur Stimulus s'initialise
    Then jsvectormap est instancié sans marqueurs (fallback gracieux)
    And un avertissement est consigné dans la console : "VectorMap : markers JSON invalide"

  Scenario: jsvectormap non chargé (erreur réseau importmap)
    Given la lib jsvectormap ne peut pas être chargée
    When la page est rendue
    Then "mapContainer" affiche le message "Carte indisponible"
    And le reste de la page reste fonctionnel sans erreur bloquante
```

## INVEST
- **Independent :** La carte vectorielle est un composant autonome, découplé des graphiques ApexCharts et du calendrier.
- **Negotiable :** La priorité `Could` signifie que cette US peut être différée ou retirée du sprint si d'autres US prioritaires consomment la vélocité.
- **Valuable :** Offre une vue géographique pertinente pour les dashboards d'e-commerce, de logistique ou d'analytics globaux.
- **Estimable :** 5 points pour un contrôleur Stimulus, un composant Twig et la gestion du thème dynamique.
- **Small :** Limité à la carte mondiale avec marqueurs ; pas de choroplèthe (remplissage par valeur) dans cette US.
- **Testable :** Vérification de la présence du SVG jsvectormap dans le DOM ; test du tooltip au survol via Panther.

## Dépendances
- **Dépend de :** US-002 (importmap et infrastructure Stimulus opérationnelle)
- **Bloque :** US-021 (page dashboard complète)

## Definition of Done
Voir `project-management/definition-of-done.md`.
