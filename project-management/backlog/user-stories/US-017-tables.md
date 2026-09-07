# US-017 — Composants Table (basiques et avancés)

**EPIC :** EPIC-004-formulaires-tables · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Should · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur**, je veux **des composants Twig de tableaux (tables basiques avec variantes et table avancée avec tri des colonnes et dropdown d'actions)**, afin de **afficher des données tabulaires dans le style TailAdmin sans réécrire le HTML et le JavaScript de chaque tableau**.

## Conversation
Les sources primaires sont `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/table/table-01.html`, `table-06.html`, `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/basic-tables.html` et les variantes Laravel dans `Tools/sources/tailadmin-laravel-main/resources/views/components/tables/basic-tables/one.blade.php` à `five.blade.php`. Les composants couvrent : **table basique** (thead fixe, lignes striped/hover, badges de statut dans les cellules), **table avec image + texte** (avatar dans la première colonne), et **table avancée** (tri côté client via un contrôleur Stimulus `table-sort-controller.js` qui gère les attributs `aria-sort` sur les `<th>`, et un dropdown d'actions par ligne qui réutilise le composant Dropdown issu de US-012). Le style est dark-mode aware (classes `dark:` Tailwind v4) et le tableau est **responsive** : sur mobile, la table défile horizontalement dans un wrapper `overflow-x-auto`. L'accessibilité est garantie par `<caption>`, `scope="col"` / `scope="row"`, rôles ARIA appropriés. L'app de démo expose `/tables/basic` et `/tables/avancee`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Composants Table TailAdmin
  Scenario: Affichage d'une table basique avec données
    Given le développeur inclut "<twig:Table:Basic :rows="users" />" avec 5 lignes
    When la page est rendue
    Then le tableau affiche 5 lignes de données avec en-tête
    And chaque cellule respecte le style TailAdmin (paddings, bordures, typographie)
    And l'attribut "scope='col'" est présent sur chaque <th> d'en-tête
```
### Scénarios alternatifs
```gherkin
  Scenario: Tri ascendant sur une colonne
    Given la table avancée est affichée avec la colonne "Nom" triable
    When l'utilisateur clique sur l'en-tête "Nom"
    Then les lignes sont réordonnées alphabétiquement (A → Z) côté client
    And l'attribut "aria-sort='ascending'" est positionné sur le <th> actif
    And un indicateur visuel (flèche) confirme le tri actif

  Scenario: Tri descendant au deuxième clic
    Given la colonne "Nom" est déjà triée en ordre ascendant
    When l'utilisateur clique une seconde fois sur l'en-tête "Nom"
    Then les lignes sont réordonnées (Z → A)
    And l'attribut "aria-sort='descending'" est mis à jour

  Scenario: Dropdown d'actions sur une ligne
    Given la table avancée affiche une colonne "Actions"
    When l'utilisateur clique sur le bouton "..." d'une ligne
    Then le dropdown du composant US-012 s'ouvre avec les actions disponibles (Éditer, Supprimer)
    And le dropdown se ferme si l'utilisateur clique en dehors

  Scenario: Affichage responsive sur mobile
    Given la table contient 8 colonnes
    When la page est affichée sur un viewport de 375 px de large
    Then la table est enveloppée dans un conteneur "overflow-x-auto"
    And l'utilisateur peut faire défiler la table horizontalement sans scroll global de la page
```
### Scénarios d'erreur
```gherkin
  Scenario: Tableau sans données
    Given le composant "<twig:Table:Basic :rows="[]" />" reçoit un tableau vide
    When la page est rendue
    Then une ligne unique affiche "Aucune donnée disponible" centrée
    And le thead et les en-têtes restent visibles

  Scenario: Tentative de tri sur une colonne non triable
    Given une colonne est déclarée sans attribut "data-sortable"
    When l'utilisateur clique sur son en-tête
    Then aucun tri n'est déclenché
    And le curseur n'indique pas d'interactivité (pas de "pointer")
```

## INVEST
- **Independent :** Les composants Table sont indépendants des composants formulaire (US-014–016) et des graphiques (US-018–019).
- **Negotiable :** La table avancée (tri + dropdown) peut être livrée séparément de la table basique si la vélocité est contrainte.
- **Valuable :** Les tableaux sont omniprésents dans tout dashboard admin ; leur disponibilité en composant Twig réutilisable réduit la dette de chaque intégrateur.
- **Estimable :** 5 points pour 3 variantes de tables, le contrôleur Stimulus de tri et la réutilisation du dropdown US-012.
- **Small :** Pas de pagination serveur ni de recherche intégrée dans cette US (reportées dans une US ultérieure).
- **Testable :** Tests snapshot Twig pour le rendu HTML ; test fonctionnel vérifiant l'ordre des lignes après tri via Panther.

## Dépendances
- **Dépend de :** US-012 (composant Dropdown, pour les actions par ligne)
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.
