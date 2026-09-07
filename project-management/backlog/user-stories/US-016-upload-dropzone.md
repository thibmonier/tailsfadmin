# US-016 — Zone d'upload avec Dropzone.js

**EPIC :** EPIC-004-formulaires-tables · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Should · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — Développeur intégrateur**, je veux **un contrôleur Stimulus encapsulant Dropzone.js (zone drag-and-drop, prévisualisation des fichiers, upload vers un endpoint configurable)**, afin de **intégrer un composant d'upload stylé TailAdmin dans un formulaire Symfony sans écrire de JavaScript ad hoc**.

## Conversation
La source de référence pour l'initialisation Dropzone est `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/js/index.js` (`new Dropzone("#demo-upload", { url: "/file/post", ... })`). Le contrôleur Stimulus `dropzone-controller.js` est placé dans `bundle/assets/controllers/` et référencé dans `importmap`. Il expose les **Values** : `url` (string, URL de l'endpoint d'upload), `maxFiles` (number, défaut `10`), `maxFilesize` (number en Mo, défaut `5`), `acceptedFiles` (string CSV, ex. `"image/*,.pdf"`), `paramName` (string, défaut `"file"`). La **Target** `previewContainer` désigne l'élément HTML recevant les miniatures générées par Dropzone. Le contrôleur dispatche un **Custom Event Stimulus** `dropzone:success` (avec le nom du fichier et la réponse serveur) et `dropzone:error` consommables par d'autres contrôleurs ou du PHP/Symfony UX. Le style de la zone de dépôt (bordure en pointillés, icône upload, texte d'invite) reproduit fidèlement le design TailAdmin et est dark-mode aware. Le composant Twig `<twig:Form:Dropzone />` génère le HTML minimal (div avec `data-controller` et `data-dropzone-*-value`). L'app de démo expose `/formulaires/upload` avec un endpoint Symfony factice renvoyant HTTP 200 + JSON `{"name":"fichier.png"}`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Zone d'upload Dropzone via Stimulus
  Scenario: Dépôt d'un fichier valide par glisser-déposer
    Given la page "/formulaires/upload" est chargée
    And la zone Dropzone est visible avec l'invite "Glissez vos fichiers ici"
    When l'utilisateur dépose un fichier image (< 5 Mo) dans la zone
    Then Dropzone affiche une miniature de prévisualisation dans "previewContainer"
    And la barre de progression atteint 100 %
    And l'événement Stimulus "dropzone:success" est émis avec le nom du fichier
```
### Scénarios alternatifs
```gherkin
  Scenario: Sélection de fichier via le clic sur la zone
    Given la zone Dropzone est affichée
    When l'utilisateur clique sur la zone (pas de drag)
    Then la boîte de dialogue système de sélection de fichier s'ouvre
    When l'utilisateur sélectionne un fichier valide
    Then le fichier est ajouté à la file d'upload et la prévisualisation s'affiche

  Scenario: Upload vers un endpoint personnalisé via Value Stimulus
    Given le composant Dropzone avec "data-dropzone-url-value='/api/upload-custom'"
    When un fichier est déposé
    Then la requête HTTP POST est envoyée à "/api/upload-custom"
    And la réponse de l'endpoint est exploitable via "dropzone:success"

  Scenario: Suppression d'un fichier ajouté avant soumission
    Given un fichier est déjà prévisualisé dans la zone
    When l'utilisateur clique sur le bouton de suppression de la miniature
    Then la miniature disparaît
    And le fichier est retiré de la liste Dropzone interne
```
### Scénarios d'erreur
```gherkin
  Scenario: Fichier dépassant la taille maximale
    Given "maxFilesize" est à 5 Mo
    When l'utilisateur dépose un fichier de 10 Mo
    Then Dropzone refuse le fichier et affiche le message "Fichier trop volumineux (max 5 Mo)"
    And l'événement "dropzone:error" est émis avec le motif du rejet
    And aucun appel HTTP n'est effectué vers l'endpoint

  Scenario: Type MIME non autorisé
    Given "acceptedFiles" est à "image/*"
    When l'utilisateur dépose un fichier ".exe"
    Then Dropzone affiche "Type de fichier non autorisé"
    And la zone revient à son état initial sans prévisualisation
```

## INVEST
- **Independent :** La zone d'upload est découplée des autres composants formulaire ; elle peut être utilisée seule dans n'importe quel template.
- **Negotiable :** La prévisualisation des fichiers non-image (PDF, etc.) peut être différée ; l'upload fonctionnel constitue le livrable prioritaire.
- **Valuable :** L'upload de fichiers est un besoin récurrent dans les dashboards admin (avatars, documents, imports CSV).
- **Estimable :** 5 points pour le contrôleur Stimulus, le composant Twig, le style TailAdmin fidèle et l'endpoint factice de démo.
- **Small :** Périmètre limité à un composant d'upload ; pas de gestion de médiathèque ni de stockage persistant.
- **Testable :** Test E2E simulant un drag-and-drop (Panther) ; vérification de l'appel HTTP vers l'endpoint et de la réponse JSON.

## Dépendances
- **Dépend de :** US-014 (composants de formulaire Twig de base)
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.
