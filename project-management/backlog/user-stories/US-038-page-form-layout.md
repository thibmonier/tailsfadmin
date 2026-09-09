# US-038 — Page « Form Layout » (gabarit de mise en page de formulaire)

**EPIC :** EPIC-010-composants-affichage-pages · **Statut :** 🔴 To Do · **Points :** 3 · **Priorité :** Could · **Sprint :** sprint-011

## Carte (Card)
> En tant que **P-001 — développeur intégrateur**, je veux **une page de démonstration présentant des gabarits de mise en page de formulaire (une/deux colonnes, sections, actions)**, afin de **copier une structure de formulaire cohérente et accessible sans repartir de zéro**.

## Conversation
Page de démo (assemblage) portée de TailAdmin (réf. [/form-layout](https://demo.tailadmin.com/form-layout)). **Aucun nouveau widget de formulaire** : la page **réutilise** les composants et le thème de formulaire existants (EPIC-004 — inputs, select, checkbox, radio, textarea, `templates/form/theme.html.twig`) et les composants `tsf:Ui:Card`, boutons, et si utile `tsf:Ui:Tabs` (US-036). Elle illustre :

- un **formulaire une colonne** (empilé) au sein d'une `tsf:Ui:Card` ;
- un **formulaire deux colonnes** responsive (grid, repli en une colonne sur mobile) ;
- un formulaire **sectionné** (plusieurs cartes/fieldsets avec titres et aides) ;
- une **barre d'actions** (annuler/enregistrer) cohérente, sticky optionnelle.

Livraison : route + template dans la démo (`demo/`), entrée dans la galerie de pages. Les libellés/erreurs passent par le système i18n (US-024). Clair/dark, responsive, accessibilité formulaire (labels liés, `aria-describedby` pour les aides/erreurs, focus visible). Aucune dépendance JS tierce.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Page de gabarits de formulaire
  Scenario: Rendu de la page Form Layout
    Given la démo expose la route "form-layout"
    When l'utilisateur ouvre la page
    Then la page répond en HTTP 200 et étend le layout admin du bundle
    And un gabarit de formulaire deux colonnes est présent
    And chaque champ possède un <label> lié à son input (for/id)
```
### Scénarios alternatifs
```gherkin
  Scenario: Repli responsive du formulaire deux colonnes
    Given la page Form Layout est affichée
    When la largeur de la fenêtre passe sous le breakpoint md
    Then le formulaire deux colonnes se réagence en une seule colonne

  Scenario: Formulaire sectionné avec aides
    Given la page présente un formulaire en plusieurs sections
    When la page est rendue
    Then chaque section porte un titre et les champs affichent leurs textes d'aide
    And les aides sont reliées aux champs via aria-describedby

  Scenario: Affichage d'erreurs de validation
    Given un champ requis est marqué en erreur (état d'exemple)
    When la page est rendue
    Then le message d'erreur est associé au champ (aria-describedby) et visible en clair/dark
```
### Scénarios d'erreur
```gherkin
  Scenario: Réutilisation stricte des composants existants
    Given la revue de code de la page Form Layout
    When le reviewer inspecte le template
    Then la page n'introduit aucun nouveau widget de formulaire (réutilise le thème EPIC-004)

  Scenario: Accessibilité clavier de la barre d'actions
    Given la page Form Layout est affichée
    When l'utilisateur navigue au clavier jusqu'aux boutons d'action
    Then les boutons sont focusables, dans l'ordre logique, avec focus visible
```

## INVEST
- **Independent :** page de démo autonome ; s'appuie sur des composants de formulaire déjà livrés.
- **Negotiable :** nombre et types de gabarits illustrés négociables avec P-002.
- **Valuable :** structure de formulaire prête à copier, gain de temps et cohérence a11y.
- **Estimable :** assemblage sans nouveau composant → 3 points.
- **Small :** une page de démo, périmètre restreint.
- **Testable :** critères couvrant rendu 200, structure, responsive, a11y et non-régression de scope.

## Dépendances
- **Dépend de :** EPIC-004 (formulaires & thème de formulaire), US-024 (i18n), US-036 (si Tabs utilisés).
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.

---

## Notes
- Recoupe EPIC-004 : ici c'est une **page-gabarit d'assemblage**, pas d'extension du système de formulaire.
- Test fonctionnel de rendu (200 + présence des gabarits + labels liés) ; revue visuelle clair/dark (P-002).
