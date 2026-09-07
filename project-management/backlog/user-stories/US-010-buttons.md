# US-010 — Composant Button

**EPIC :** EPIC-003-composants-ui · **Statut :** 🔴 To Do · **Points :** 3 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — développeur intégrateur**, je veux **un composant Twig Button déclinable en 6 variantes avec gestion des tailles, icônes et états**, afin de **disposer d'un seul point de vérité pour tous les boutons de l'interface d'administration, cohérents visuellement et accessibles**.

## Conversation
Le composant Button couvre 6 variantes issues de `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/buttons/button-01.html` à `button-06.html` : primary (plein), secondary (gris), outline (bordure), ghost (transparent), danger (rouge), success (vert). Props : `variant` (enum, défaut primary), `size` (sm|md|lg, défaut md), `type` (button|submit|reset, défaut button), `label` (string), `iconLeft` (nom d'icône SVG ou null), `iconRight` (nom d'icône SVG ou null), `disabled` (bool, défaut false), `loading` (bool, défaut false). Quand `loading=true`, un spinner SVG remplace l'icône gauche et `aria-busy="true"` est ajouté ; le bouton est également désactivé fonctionnellement. L'icône est injectée via un helper Twig `tailsadmin_icon(name)` qui inline le SVG depuis un sprite ou un fichier partiel. La référence Laravel est `Tools/sources/tailadmin-laravel-main/resources/views/components/ui/button.blade.php`. Le composant est purement statique (aucun Stimulus) ; les comportements de clic restent à la charge de la page hôte. Dark mode via classes `dark:`. Classe PHP `ButtonComponent`, template `components/button.html.twig`. Livraison dans le bundle `TailsAdmin` ; démo dans `templates/demo/components/buttons.html.twig`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Rendu d'un bouton primary standard
  Scenario: Bouton primary taille md sans icône
    Given le développeur intègre <twig:TailsAdmin:Button variant="primary" label="Enregistrer" />
    When la page est rendue
    Then un élément <button type="button"> est présent dans le DOM
    And le bouton affiche le texte "Enregistrer"
    And le fond du bouton est bleu primaire (classes Tailwind CSS v4 correspondantes)
    And le bouton est focusable et accessible au clavier
```
### Scénarios alternatifs
```gherkin
  Scenario: Bouton outline avec icône à gauche
    Given le développeur intègre <twig:TailsAdmin:Button variant="outline" label="Exporter" iconLeft="download" />
    When la page est rendue
    Then le bouton affiche l'icône SVG "download" à gauche du texte "Exporter"
    And le bouton a une bordure sans fond plein

  Scenario: Bouton en état loading
    Given le développeur intègre <twig:TailsAdmin:Button variant="primary" label="Enregistrer" loading="true" />
    When la page est rendue
    Then le bouton affiche un spinner SVG à la place de l'icône gauche
    And l'attribut aria-busy="true" est présent
    And l'attribut disabled est positionné sur le bouton

  Scenario: Bouton danger taille small
    Given le développeur intègre <twig:TailsAdmin:Button variant="danger" label="Supprimer" size="sm" />
    When la page est rendue
    Then le bouton est affiché en rouge avec les classes de taille réduite (text-sm, px-3, py-1.5)
```
### Scénarios d'erreur
```gherkin
  Scenario: Variant invalide
    Given le développeur passe variant="link" (non supporté)
    When le composant est rendu en mode debug Symfony
    Then une exception LogicException est levée : "Variante de bouton invalide : link."

  Scenario: Label vide
    Given le développeur intègre <twig:TailsAdmin:Button variant="primary" label="" />
    When le composant est rendu en mode debug
    Then une exception LogicException est levée : "Le label du bouton ne peut pas être vide."
```

## INVEST
- **Independent :** Composant autonome sans dépendance à d'autres composants UI en cours ; les tokens et le layout sont des prérequis stables.
- **Negotiable :** La gestion des icônes (sprite SVG vs fichiers individuels) est négociable avec le lead designer (P-002) ; le spinner de loading pourrait être un Twig partial séparé.
- **Valuable :** Tous les formulaires et actions utilisent des boutons ; un composant centralisé garantit cohérence et respect du design system.
- **Estimable :** 6 variantes de style CSS, 1 classe PHP, pas de JavaScript → 3 points.
- **Small :** 3 points, clairement borné ; les boutons icône-seul (icon-only) sont exclus du scope (YAGNI).
- **Testable :** Critères couvrent variantes, tailles, icônes, disabled, loading, et cas d'erreur de prop.

## Dépendances
- **Dépend de :** US-002 (Tailwind/tokens), US-004 (layout de base)
- **Bloque :** US-014, US-023

## Definition of Done
Voir `project-management/definition-of-done.md`.
