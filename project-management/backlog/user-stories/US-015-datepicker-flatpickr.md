# US-015 — Sélecteur de date / plage avec flatpickr

**EPIC :** EPIC-004-formulaires-tables · **Statut :** 🔴 To Do · **Points :** 3 · **Priorité :** Should · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur**, je veux **un contrôleur Stimulus encapsulant flatpickr (mode calendrier simple et mode plage de dates)**, afin de **ajouter un sélecteur de date stylé TailAdmin à n'importe quel champ de formulaire sans copier-coller de JavaScript**.

## Conversation
Le comportement de référence se trouve dans `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/datepicker.html` (HTML) et dans l'initialisation flatpickr de `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/js/index.js` (`flatpickr(".flatpickr", { mode: "range", ... })`). Le contrôleur Stimulus `datepicker-controller.js` est placé dans `bundle/assets/controllers/` et déclaré dans `importmap` ; il expose les **Values** : `mode` (string, défaut `"single"`), `dateFormat` (string, défaut `"Y-m-d"`), `minDate` / `maxDate` (string optionnels). La lib flatpickr est chargée via `importmap` (CDN jsdelivr ou package local). Le contrôleur détecte le thème sombre via `document.documentElement.classList.contains('dark')` et applique le thème flatpickr `dark` en conséquence ; il réagit aussi aux changements de thème en temps réel via un `MutationObserver`. L'input sous-jacent conserve la valeur ISO (`Y-m-d`) pour la soumission Symfony Form. Le composant Twig `<twig:Form:Datepicker />` (du bundle) génère l'input avec `data-controller="datepicker"` et les `data-datepicker-*-value` appropriés. L'app de démo illustre les deux modes sur `/formulaires/datepicker`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Sélecteur de date flatpickr via Stimulus
  Scenario: Sélection d'une date unique
    Given la page "/formulaires/datepicker" est chargée
    And un champ Datepicker en mode "single" est visible
    When l'utilisateur clique sur le champ
    Then le calendrier flatpickr s'ouvre
    When l'utilisateur sélectionne le 15 du mois courant
    Then le champ affiche la date formatée selon "dateFormat"
    And la valeur de l'input caché est au format ISO "Y-m-d"
```
### Scénarios alternatifs
```gherkin
  Scenario: Sélection d'une plage de dates
    Given un champ Datepicker avec la value Stimulus "mode" à "range"
    When l'utilisateur sélectionne une date de début puis une date de fin
    Then le champ affiche "YYYY-MM-DD à YYYY-MM-DD"
    And les deux dates sont accessibles via les champs cachés du formulaire

  Scenario: Datepicker en thème sombre
    Given le thème sombre est actif sur la page (classe "dark" sur <html>)
    When l'utilisateur ouvre le calendrier
    Then flatpickr utilise le thème sombre (fond foncé, texte clair)
    And aucune feuille de style supplémentaire n'est nécessaire côté intégrateur
```
### Scénarios d'erreur
```gherkin
  Scenario: Date saisie hors de la plage autorisée
    Given un champ Datepicker avec "minDate" à "2025-01-01" et "maxDate" à "2025-12-31"
    When l'utilisateur tente de sélectionner le 01/01/2024
    Then flatpickr grise la date et empêche la sélection
    And aucune valeur invalide n'est écrite dans l'input caché

  Scenario: flatpickr non chargé (erreur réseau importmap)
    Given la lib flatpickr ne peut pas être chargée (réseau indisponible)
    When la page est rendue
    Then le champ input[type=text] reste fonctionnel comme champ texte natif
    And une erreur non bloquante est consignée dans la console navigateur
```

## INVEST
- **Independent :** Cette US couvre uniquement le datepicker ; les autres éléments de formulaire sont dans US-014.
- **Negotiable :** Le mode `range` peut être livré en deuxième itération si le temps manque ; le mode `single` constitue un livrable minimum.
- **Valuable :** La sélection de dates est omniprésente dans les dashboards (filtres, rapports) ; évite à chaque intégrateur d'initialiser flatpickr manuellement.
- **Estimable :** 3 points pour un seul contrôleur Stimulus avec deux modes bien documentés.
- **Small :** Périmètre restreint à flatpickr ; pas d'intégration calendrier FullCalendar (US-020).
- **Testable :** Test fonctionnel Panther/Playwright vérifiant l'ouverture du calendrier et la valeur ISO soumise.

## Dépendances
- **Dépend de :** US-014 (composants de formulaire Twig de base)
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.
