# US-012 — Composant Dropdown

**EPIC :** EPIC-003-composants-ui · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — développeur intégrateur**, je veux **un composant Dropdown accessible piloté par Stimulus (converti depuis Alpine.js)**, afin de **proposer des menus contextuels navigables au clavier dans le header, les tableaux et les cartes, sans dépendance Alpine résiduelle**.

## Conversation
Le Dropdown remplace les implémentations Alpine.js (`x-show`, `@click.outside`, `@keydown`) par un Stimulus controller (`dropdown_controller.js`). Sources primaires : les dropdowns du header (utilisateur connecté, notifications) dans les partials TailAdmin, `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/` ; référence Laravel : `Tools/sources/tailadmin-laravel-main/resources/views/components/common/dropdown-menu.blade.php` et `table-dropdown.blade.php`. Le composant Twig expose : `id` (string, requis pour ARIA), `trigger` (block Twig ou label string), `align` (left|right, défaut left), `width` (auto|sm|md|lg, défaut md). Le panneau de menu est rendu via un Twig block `items`. Le **Stimulus controller** gère : ouverture/fermeture au clic sur le trigger (`toggle()`), fermeture sur clic extérieur (`click@window->dropdown#closeIfOutside`), navigation clavier : `ArrowDown`/`ArrowUp` déplacent le focus entre les items, `Home`/`End` sautent en début/fin, `Échap` ferme et replace le focus sur le trigger, `Enter`/`Space` activent l'item focalisé. ARIA : `role="menu"` sur le panneau, `role="menuitem"` sur chaque item, `aria-expanded` sur le trigger, `aria-haspopup="true"`. Positionnement via classes Tailwind (`absolute`, `right-0`/`left-0`). Dark mode via `dark:`. Le composant est livré dans le bundle `TailsAdmin` ; démo dans `templates/demo/components/dropdowns.html.twig`. Il sera réutilisé par US-007 (header) et US-017 (tableau).

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Ouverture d'un menu Dropdown
  Scenario: Ouvrir le dropdown au clic sur le trigger
    Given un composant Dropdown avec id="user-menu" et un trigger "Mon compte" est dans le DOM (panneau masqué, aria-expanded="false")
    When l'utilisateur clique sur le trigger "Mon compte"
    Then le panneau de menu est visible
    And aria-expanded="true" est positionné sur le trigger
    And role="menu" est présent sur le panneau
    And le focus est placé sur le premier menuitem
```
### Scénarios alternatifs
```gherkin
  Scenario: Fermeture du dropdown par clic extérieur
    Given le dropdown "user-menu" est ouvert
    When l'utilisateur clique en dehors du composant
    Then le panneau est masqué
    And aria-expanded="false" est restauré
    And le focus est retourné au trigger

  Scenario: Navigation clavier dans le menu
    Given le dropdown est ouvert et contient 4 items
    When l'utilisateur appuie sur ArrowDown depuis le premier item
    Then le focus se déplace au second item
    When l'utilisateur appuie sur ArrowDown 3 fois supplémentaires
    Then le focus revient au premier item (navigation circulaire)

  Scenario: Fermeture par touche Échap
    Given le dropdown "user-menu" est ouvert
    When l'utilisateur appuie sur Échap
    Then le panneau est masqué
    And le focus est retourné au trigger

  Scenario: Dropdown aligné à droite
    Given le développeur intègre le Dropdown avec align="right"
    When le panneau est ouvert
    Then le panneau est positionné avec la classe "right-0" (aligné bord droit du trigger)
```
### Scénarios d'erreur
```gherkin
  Scenario: Prop id manquant
    Given le développeur intègre <twig:TailsAdmin:Dropdown align="left" /> sans id
    When le composant est rendu en mode debug Symfony
    Then une exception LogicException est levée : "Le prop id est obligatoire pour le Dropdown (requis par ARIA)."

  Scenario: Aucun item dans le bloc menu
    Given le développeur intègre un Dropdown avec un block items vide
    When l'utilisateur ouvre le dropdown
    Then le panneau s'ouvre mais affiche un état vide (pas d'erreur JavaScript)
    And un avertissement Symfony de debug est émis en mode dev : "Dropdown vide rendu."
```

## INVEST
- **Independent :** Le Dropdown est autonome ; il sera référencé par US-007 et US-017 mais n'en dépend pas pour être développé et testé.
- **Negotiable :** La navigation `Home`/`End` est négociable (bonus WCAG, peut être différée) ; l'ouverture au survol (hover) est hors scope YAGNI.
- **Valuable :** Header, tableaux d'actions, filtres : le Dropdown est omniprésent ; sa conversion Alpine → Stimulus est obligatoire pour la décision d'architecture.
- **Estimable :** Stimulus controller avec navigation clavier complète + ARIA → 5 points (complexité a11y similaire à la Modal).
- **Small :** 5 points, borné ; le Popover/Tooltip est exclu (autre composant).
- **Testable :** Critères couvrent ouverture, fermeture (clic extérieur + Échap), navigation clavier, alignement, ARIA, et cas d'erreur.

## Dépendances
- **Dépend de :** US-002 (Tailwind/tokens), US-004 (layout de base)
- **Bloque :** US-007, US-017

## Definition of Done
Voir `project-management/definition-of-done.md`.
