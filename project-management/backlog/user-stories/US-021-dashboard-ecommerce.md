# US-021 — Dashboard e-commerce

**EPIC :** EPIC-006-pages-i18n · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur**, je veux **assembler la page de tableau de bord e-commerce en composant les blocs fournis par le bundle (métriques, cible mensuelle, graphiques, carte démographique, tableau de commandes)**, afin de **valider que tous les composants UI s'intègrent correctement dans une page cohérente et démontrer la valeur du bundle dans l'application de démo**.

## Conversation
La page principale du dashboard (`src/index.html` dans les sources TailAdmin) regroupe plusieurs zones distinctes, chacune déjà couverte par une US amont : métriques e-commerce (`ecommerce-metrics`, US-013/cards), cible mensuelle (`monthly-target`, US-013), ventes mensuelles (`monthly-sale`, US-018/charts), graphique de statistiques (US-018), démographie client avec carte choroplèthe (US-019/map), tableau des commandes récentes (US-013/badges US-009). Les sources Laravel de référence se trouvent dans `Tools/sources/tailadmin-laravel-main/resources/views/components/ecommerce/` (`ecommerce-metrics.blade.php`, `monthly-target.blade.php`, `monthly-sale.blade.php`, `recent-orders.blade.php`, `demographic-card.blade.php`). La page de démo instancie chaque composant via `<twig:Tailsfadmin:EcommerceMetrics />`, `<twig:Tailsfadmin:MonthlyTarget />`, etc. Le layout utilise la grille Tailwind v4 avec breakpoints `lg:grid-cols-3` et comportement responsive (colonne unique sur mobile). Le dark mode doit être fonctionnel via la classe CSS `dark:` sur chaque composant. Aucun code de composant n'est réécrit ici : seule l'assemblage de la page démo (`demo/templates/dashboard/index.html.twig`) est dans le scope de cette US.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Dashboard e-commerce assemblé
  Scenario: Affichage complet du tableau de bord en mode clair
    Given l'application de démo est démarrée et l'utilisateur est sur la route "/"
    When la page se charge dans un navigateur sans thème dark
    Then la zone métriques e-commerce affiche au moins 4 cartes KPI avec icône et valeur
    And la zone cible mensuelle affiche la jauge circulaire et le pourcentage atteint
    And le graphique de ventes mensuelles est rendu sans erreur JS dans la console
    And la carte démographique affiche les régions colorées
    And le tableau des commandes récentes contient au moins une ligne avec badge de statut
```
### Scénarios alternatifs
```gherkin
  Scenario: Affichage en mode dark
    Given la préférence système est "dark" ou la classe "dark" est active sur <html>
    When l'utilisateur visite la page dashboard
    Then tous les composants utilisent les couleurs de fond et de texte du mode sombre
    And aucun composant ne présente un fond blanc sur fond sombre

  Scenario: Affichage sur écran mobile (< 768 px)
    Given la largeur de la fenêtre est fixée à 375 px
    When l'utilisateur visite la page dashboard
    Then les composants sont empilés verticalement en colonne unique
    And aucun débordement horizontal n'est visible (overflow-x: hidden respecté)

  Scenario: Composants chargés indépendamment via Twig Components
    Given le développeur instancie uniquement "<twig:Tailsfadmin:EcommerceMetrics />" dans un template test
    When Symfony rend ce template
    Then le bloc HTML du composant est rendu sans erreur et sans dépendance vers d'autres composants de la page
```
### Scénarios d'erreur
```gherkin
  Scenario: Composant manquant dans le bundle
    Given le développeur supprime le fichier de template d'un composant du bundle
    When Symfony tente de rendre la page dashboard
    Then une exception Twig "Unable to find component" est levée avec le nom du composant manquant
    And le message d'erreur indique le chemin attendu dans le bundle

  Scenario: Données JSON vides transmises au graphique
    Given le contrôleur démo retourne un tableau de ventes vide pour le graphique
    When la page se charge
    Then le graphique affiche un état vide avec un message "Aucune donnée disponible"
    And aucune erreur JavaScript n'est émise dans la console du navigateur
```

## INVEST
- **Independent :** Assemble des composants déjà livrés (US-009, US-013, US-018, US-019) ; pas de nouveaux composants à créer.
- **Negotiable :** L'ordre et la disposition des blocs peuvent être revus ; la grille exacte (2 ou 3 colonnes) est ajustable selon retour design.
- **Valuable :** Fournit la vitrine principale du bundle ; c'est la page la plus visible de la démo pour un intégrateur qui évalue tailsfadmin.
- **Estimable :** Travail d'assemblage Twig + ajustements responsive ; estimé 5 points car la logique est dans les composants amont.
- **Small :** Limité à un seul fichier template de démo + un contrôleur avec données fictives ; aucun nouveau composant bundle.
- **Testable :** Critères visuels et structurels objectifs, vérifiables par test fonctionnel Panther ou snapshot Twig.

## Dépendances
- **Dépend de :** US-009 (badges/avatars), US-013 (cards/métriques), US-018 (charts), US-019 (carte démographique)
- **Bloque :** US-025 (audit a11y — page assemblée requise)

## Definition of Done
Voir `project-management/definition-of-done.md`.
