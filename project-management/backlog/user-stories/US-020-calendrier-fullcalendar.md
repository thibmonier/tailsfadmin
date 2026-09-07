# US-020 — Calendrier interactif avec FullCalendar

**EPIC :** EPIC-005-dataviz-calendrier · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Should · **Sprint :** backlog

## Carte (Card)
> En tant que **P-004 — Utilisateur final (admin)**, je veux **consulter, créer et modifier des événements dans un calendrier interactif (vues jour/semaine/mois/liste)**, afin de **gérer mon planning directement depuis le dashboard sans quitter l'interface**.

## Conversation
Les sources de référence sont `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/js/components/calendar-init.js` (initialisation FullCalendar avec les plugins `dayGrid`, `timeGrid`, `list` et `interaction`), `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/calendar-event-modal.html` (modal de création/édition d'événement) et `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/calendar.html` (page complète). Le contrôleur Stimulus `fullcalendar-controller.js` est placé dans `bundle/assets/controllers/` et référencé dans `importmap`. Il expose les **Values** : `events` (JSON, tableau d'événements FullCalendar), `initialView` (string, défaut `"dayGridMonth"`), `locale` (string, défaut `"fr"`), `editable` (boolean, défaut `true`). Les **Targets** : `calendarContainer` (montage FullCalendar), `modal` (modal de création/édition réutilisant le composant Modal US-011). Le contrôleur dispatch les événements Stimulus `calendar:eventClick`, `calendar:dateClick`, `calendar:eventDrop` pour permettre à l'intégrateur d'accrocher sa propre logique (appel API, mise à jour Symfony). FullCalendar et ses plugins (`@fullcalendar/core`, `@fullcalendar/daygrid`, `@fullcalendar/timegrid`, `@fullcalendar/list`, `@fullcalendar/interaction`) sont chargés via `importmap`. Le style est dark-mode aware (surcharge CSS Tailwind des classes FullCalendar). La modal d'événement permet de renseigner titre, date de début, date de fin et couleur. L'app de démo expose `/calendrier` avec des événements fictifs.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Calendrier FullCalendar interactif via Stimulus
  Scenario: Affichage du calendrier mensuel avec événements
    Given la page "/calendrier" est chargée
    And la Value "events" contient 3 événements dans le mois courant
    When FullCalendar est monté dans "calendarContainer"
    Then la vue mensuelle (dayGridMonth) est affichée par défaut
    And les 3 événements apparaissent dans leurs jours respectifs avec leurs titres
    And les boutons de navigation (précédent / suivant / aujourd'hui) sont fonctionnels
```
### Scénarios alternatifs
```gherkin
  Scenario: Changement de vue (mois → semaine)
    Given le calendrier est en vue mensuelle
    When l'utilisateur clique sur le bouton "Semaine" dans la barre de navigation FullCalendar
    Then la vue bascule en "timeGridWeek"
    And les événements sont repositionnés selon les plages horaires

  Scenario: Création d'un événement via clic sur une date
    Given le calendrier est en vue mensuelle et "editable" est à true
    When l'utilisateur clique sur un jour vide
    Then la modal d'événement s'ouvre (composant US-011)
    And les champs "Titre", "Date de début" et "Date de fin" sont pré-remplis avec la date cliquée
    When l'utilisateur saisit un titre et confirme
    Then l'événement Stimulus "calendar:dateClick" est dispatché avec les données du nouvel événement
    And l'intégrateur peut l'utiliser pour persister l'événement via une API Symfony

  Scenario: Déplacement d'un événement par glisser-déposer
    Given un événement est affiché dans le calendrier
    When l'utilisateur le fait glisser vers une autre date
    Then l'événement se déplace visuellement
    And l'événement Stimulus "calendar:eventDrop" est dispatché avec les nouvelles dates

  Scenario: Affichage en thème sombre
    Given le thème sombre est actif sur la page
    When le calendrier est rendu
    Then la grille, les en-têtes de jours et les événements respectent la palette dark TailAdmin
    And le contraste des textes est conforme WCAG AA
```
### Scénarios d'erreur
```gherkin
  Scenario: JSON des événements malformé
    Given la Value "events" contient du JSON invalide
    When le contrôleur Stimulus s'initialise
    Then FullCalendar est instancié sans événements (fallback gracieux)
    And un avertissement est consigné dans la console : "FullCalendar : events JSON invalide"
    And le calendrier vide reste interactif (navigation possible)

  Scenario: FullCalendar non chargé (erreur réseau importmap)
    Given les modules FullCalendar ne peuvent pas être chargés
    When la page est rendue
    Then "calendarContainer" affiche le message "Calendrier indisponible"
    And le reste de la page reste fonctionnel sans erreur bloquante JavaScript
```

## INVEST
- **Independent :** Le composant calendrier est découplé des graphiques ApexCharts et de la carte vectorielle.
- **Negotiable :** La création/édition d'événements via modal peut être livrée en deuxième itération ; l'affichage seul constitue un livrable autonome.
- **Valuable :** Le calendrier est l'un des composants les plus demandés dans un dashboard admin professionnel (planning, RDV, tâches).
- **Estimable :** 8 points pour le contrôleur Stimulus multi-plugin, la modal d'événement (réutilisant US-011) et l'adaptation dark mode FullCalendar.
- **Small :** Pas de persistance backend dans cette US (les événements sont passés en JSON statique) ; la synchronisation API est déléguée aux événements Stimulus dispatché.
- **Testable :** Test fonctionnel vérifiant l'affichage des événements et l'ouverture de la modal ; vérification des Custom Events Stimulus via Panther.

## Dépendances
- **Dépend de :** US-011 (composant Modal réutilisé pour la création/édition d'événement)
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.
