# US-014 — Composants de formulaire Twig (form elements)

**EPIC :** EPIC-004-formulaires-tables · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur**, je veux **disposer d'une bibliothèque de composants Twig couvrant l'ensemble des éléments de formulaire (inputs, selects, checkboxes, radios, toggles, textareas, groupes) avec leurs états visuels**, afin de **construire des formulaires Symfony cohérents avec le design TailAdmin sans réécrire le HTML à chaque usage**.

## Conversation
Les composants doivent couvrir : input text/email/password/number, input-group (icône préfixe/suffixe), select simple et multiple, checkbox, radio, toggle switch, textarea — chacun dans les états `default`, `success`, `error` et `disabled`. Les sources primaires sont `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/form-elements.html` et les composants Laravel dans `Tools/sources/tailadmin-laravel-main/resources/views/components/form/form-elements/*`. Chaque composant est implémenté comme un **Twig Component** (namespace `Tailsfadmin\TailsfadminBundle\Twig\Components\Form\`) avec des props typées PHP 8.5, exposés dans le bundle. Un **form theme Symfony** (`tailsfadmin_form_theme.html.twig`) est fourni dans le bundle de façon à ce que les formulaires Symfony générés via `$form->createView()` adoptent automatiquement le style TailAdmin. Tous les composants sont dark-mode aware (classes `dark:` Tailwind v4), responsive (full-width mobile) et respectent les attributs ARIA (`aria-describedby` pour les messages d'erreur, `aria-invalid`, `aria-required`). L'app de démo expose une route `/formulaires/elements` qui illustre tous les composants dans leurs états.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Composants de formulaire TailAdmin
  Scenario: Affichage d'un input texte en état default
    Given le développeur inclut le composant "<twig:Form:Input type='text' label='Nom' />"
    When la page est rendue dans la démo
    Then le champ affiche un label "Nom" associé via "for/id"
    And le champ a la classe Tailwind correspondant au style TailAdmin
    And le rendu est identique en thème clair et thème sombre (classes dark:)
```
### Scénarios alternatifs
```gherkin
  Scenario: Input en état error avec message de validation
    Given un champ input dont la prop "state" vaut "error" et "errorMessage" vaut "Champ requis"
    When la page est rendue
    Then le champ affiche une bordure rouge et le message "Champ requis" sous le champ
    And l'attribut "aria-invalid" vaut "true"
    And l'attribut "aria-describedby" pointe vers l'id du message d'erreur

  Scenario: Toggle switch coché/décoché
    Given un composant "<twig:Form:Toggle checked=true label='Activer' />"
    When l'utilisateur clique sur le toggle
    Then l'état visuel bascule (on ↔ off)
    And le champ input[type=checkbox] sous-jacent change de valeur

  Scenario: Select multiple avec options pré-sélectionnées
    Given un composant "<twig:Form:Select multiple=true>" avec 5 options dont 2 pré-sélectionnées
    When la page est rendue
    Then les 2 options pré-sélectionnées sont visuellement marquées
    And l'attribut "multiple" est présent sur l'élément <select>

  Scenario: Formulaire Symfony utilisant le form theme
    Given un FormType Symfony dont le thème actif est "tailsfadmin_form_theme.html.twig"
    When le formulaire est rendu via "form_widget(form)"
    Then chaque champ adopte le style TailAdmin sans HTML supplémentaire côté contrôleur
```
### Scénarios d'erreur
```gherkin
  Scenario: Prop "type" invalide sur le composant Input
    Given un composant "<twig:Form:Input type='color' />" (type non supporté)
    When Twig rend le composant en environnement de développement
    Then une exception Twig est levée avec le message "Type de champ non supporté : color"
    And aucune page d'erreur silencieuse n'est retournée

  Scenario: Champ disabled non modifiable par l'utilisateur
    Given un composant "<twig:Form:Input disabled=true value='Lecture seule' />"
    When l'utilisateur tente de saisir une valeur dans le champ
    Then le navigateur refuse toute saisie (attribut "disabled" présent dans le DOM)
    And la valeur soumise dans le formulaire est ignorée côté serveur Symfony
```

## INVEST
- **Independent :** Les composants form sont indépendants des composants navigation/layout déjà définis dans les US précédentes.
- **Negotiable :** La liste exacte des états visuels et des variantes de composants peut être réduite en cours de sprint si la vélocité est contrainte.
- **Valuable :** Fondation indispensable pour tous les écrans de saisie de l'admin ; le form theme Symfony multiplie la valeur pour les intégrateurs.
- **Estimable :** 8 points reflètent la variété des composants, la création du form theme et les tests PHPUnit/Twig associés.
- **Small :** Limitée aux éléments de formulaire purs ; la datepicker et le dropzone sont séparés dans US-015 et US-016.
- **Testable :** Chaque état de chaque composant est vérifiable par snapshot Twig et test fonctionnel Symfony.

## Dépendances
- **Dépend de :** US-010 (layout et système de thème Tailwind v4 opérationnel)
- **Bloque :** US-015 (datepicker), US-016 (dropzone)

## Definition of Done
Voir `project-management/definition-of-done.md`.
