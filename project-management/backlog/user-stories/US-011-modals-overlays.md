# US-011 — Composant Modal et Overlay

**EPIC :** EPIC-003-composants-ui · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — développeur intégrateur**, je veux **un composant Modal accessible piloté par Stimulus (converti depuis Alpine.js)**, afin de **afficher des dialogues de confirmation, formulaires ou informations contextuelles sans dépendance Alpine résiduelle, avec focus trap et gestion clavier conformes WCAG**.

## Conversation
La Modal remplace l'implémentation Alpine.js originale (`x-show`, `@click`, `@keydown.escape`) par un Stimulus controller (`modal_controller.js`). Sources primaires : `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/overlay.html` pour l'overlay de fond, ainsi que les modales profil et calendrier dans les partials du même répertoire. Référence Laravel : `Tools/sources/tailadmin-laravel-main/resources/views/components/ui/modal.blade.php`. Le composant Twig expose : `id` (string, requis pour l'ARIA), `title` (string), `size` (sm|md|lg|xl, défaut md), `dismissible` (bool, défaut true). Le **Stimulus controller** gère : ouverture (`open()`) et fermeture (`close()`), focus trap complet (premier/dernier élément focusable), fermeture sur touche Échap (`keydown.esc`), fermeture sur clic sur l'overlay (`click->modal#closeOnBackdrop`), verrouillage du scroll body (`overflow-hidden`). ARIA : `role="dialog"`, `aria-modal="true"`, `aria-labelledby` pointant vers le titre, `aria-hidden` sur les éléments hors dialog quand ouvert. Le bouton déclencheur externe utilise `data-action="click->modal#open" data-modal-target-value="modal-id"`. Dark mode via classes `dark:`. Livraison dans le bundle `TailsAdmin` ; démo dans `templates/demo/components/modals.html.twig`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Ouverture et fermeture d'une modal
  Scenario: Ouvrir une modal via un bouton déclencheur
    Given une modal avec id="confirm-delete" et title="Confirmer la suppression" est présente dans le DOM (aria-hidden="true")
    And un bouton portant data-action="click->modal#open" data-modal-target-value="confirm-delete" est présent
    When l'utilisateur clique sur le bouton déclencheur
    Then la modal est visible (aria-hidden="false")
    And role="dialog" et aria-modal="true" sont présents
    And le focus est placé sur le premier élément focusable de la modal
    And le scroll du body est verrouillé
```
### Scénarios alternatifs
```gherkin
  Scenario: Fermeture de la modal via la touche Échap
    Given la modal "confirm-delete" est ouverte
    When l'utilisateur appuie sur la touche Échap
    Then la modal est masquée (aria-hidden="true")
    And le focus est retourné à l'élément déclencheur
    And le scroll du body est restauré

  Scenario: Fermeture via clic sur l'overlay de fond
    Given la modal est ouverte et dismissible="true"
    When l'utilisateur clique sur l'overlay semi-transparent hors du panneau de la modal
    Then la modal est masquée

  Scenario: Modal non dismissible par overlay
    Given une modal avec dismissible="false" est ouverte
    When l'utilisateur clique sur l'overlay
    Then la modal reste ouverte

  Scenario: Focus trap — navigation clavier circulaire
    Given la modal est ouverte et contient 3 éléments focusables
    When l'utilisateur appuie sur Tab depuis le dernier élément
    Then le focus revient au premier élément focusable de la modal
    And le focus ne quitte jamais le panneau de la modal
```
### Scénarios d'erreur
```gherkin
  Scenario: Prop id manquant
    Given le développeur intègre <twig:TailsAdmin:Modal title="Titre" /> sans id
    When le composant est rendu en mode debug Symfony
    Then une exception LogicException est levée : "Le prop id est obligatoire pour la Modal (requis par ARIA)."

  Scenario: Stimulus controller absent du bundle JS
    Given le controller modal_controller.js n'est pas importé dans l'importmap
    When l'utilisateur clique sur le bouton déclencheur
    Then aucune erreur JS fatale n'est levée (dégradation gracieuse)
    And la console affiche un avertissement Stimulus indiquant le controller manquant
```

## INVEST
- **Independent :** La Modal ne dépend d'aucun autre composant en cours ; elle est utilisable seule via un bouton HTML standard.
- **Negotiable :** L'animation d'entrée/sortie (fade, slide) est négociable ; le focus trap est non-négociable (exigence WCAG 2.1 AA).
- **Valuable :** Dialogues de confirmation, formulaires inline, galeries : la modal est l'un des composants les plus réutilisés d'un admin.
- **Estimable :** Conversion Alpine → Stimulus avec focus trap et ARIA complet → 5 points (complexité d'accessibilité justifie +2 vs composant statique).
- **Small :** 5 points, bien borné ; le drawer/side-panel est exclu (autre US).
- **Testable :** Critères couvrent ouverture, fermeture (3 modes), focus trap, ARIA, prop manquant, dégradation JS.

## Dépendances
- **Dépend de :** US-002 (Tailwind/tokens), US-004 (layout de base)
- **Bloque :** US-020, US-022

## Definition of Done
Voir `project-management/definition-of-done.md`.
