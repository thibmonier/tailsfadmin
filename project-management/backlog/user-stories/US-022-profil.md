# US-022 — Page profil utilisateur

**EPIC :** EPIC-006-pages-i18n · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Should · **Sprint :** backlog

## Carte (Card)
> En tant que **P-004 — Utilisateur final de l'interface admin**, je veux **consulter et modifier mon profil (informations personnelles et adresse) depuis une page dédiée avec modals d'édition**, afin de **maintenir mes données à jour sans quitter l'interface d'administration**.

## Conversation
La page profil TailAdmin (`src/profile.html`) se compose de trois zones principales : une carte d'en-tête profil (avatar, nom, rôle, statistiques de contenu), un formulaire d'informations personnelles en lecture seule avec bouton d'édition ouvrant une modal, et une section adresse avec sa propre modal. Les sources HTML de référence sont `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/profile.html`, `src/partials/profile/profile-info-modal.html` et `src/partials/profile/profile-address-modal.html`. Les composants Laravel équivalents se trouvent dans `Tools/sources/tailadmin-laravel-main/resources/views/components/profile/` (`user-info-card.blade.php`, `profile-info-form.blade.php`, `profile-address-form.blade.php`). L'avatar utilise le composant bundle déjà livré (US-009). Les modals s'appuient sur le composant bundle Modal (US-011) piloté par un contrôleur Stimulus `modal-controller` défini dans le bundle. La page est dans l'app de démo ; les composants réutilisables (`ProfileCard`, `ProfileInfoForm`, `ProfileAddressForm`) sont dans le bundle et instanciés via `<twig:Tailsfadmin:ProfileCard />`. Les champs du formulaire utilisent les composants Form du bundle (US-014). Le dark mode et le responsive (grille 1 → 2 colonnes sur `lg`) sont obligatoires. L'accessibilité des modals (focus trap, `aria-modal`, fermeture Échap) est couverte par US-025 mais doit être pré-câblée ici.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Page profil utilisateur
  Scenario: Affichage de la page profil avec toutes ses zones
    Given l'utilisateur de démo est authentifié et navigue vers "/profile"
    When la page se charge
    Then la carte profil affiche l'avatar, le nom "John Doe" et le rôle "Product Designer"
    And la section informations personnelles affiche les champs Email, Téléphone, Bio en mode lecture
    And la section adresse affiche Pays, Ville, Adresse postale en mode lecture
    And les boutons "Modifier" sont visibles dans chaque section
```
### Scénarios alternatifs
```gherkin
  Scenario: Ouverture de la modal d'édition des informations personnelles
    Given l'utilisateur est sur la page "/profile"
    When il clique sur le bouton "Modifier" de la section informations personnelles
    Then la modal "Modifier les informations" s'ouvre avec les champs pré-remplis
    And le focus est placé sur le premier champ de la modal
    And l'arrière-plan de la page est assombri (overlay)

  Scenario: Fermeture de la modal sans sauvegarder
    Given la modal d'édition des informations est ouverte
    When l'utilisateur presse la touche Échap ou clique sur le bouton "Annuler"
    Then la modal se ferme sans modifier les données affichées dans la page
    And le focus retourne sur le bouton "Modifier" qui avait déclenché l'ouverture

  Scenario: Affichage en mode dark
    Given la classe "dark" est active sur l'élément <html>
    When l'utilisateur est sur la page profil
    Then la carte profil, les sections et les modals utilisent les couleurs dark définies par le bundle
    And les champs de formulaire affichent un fond sombre avec texte clair
```
### Scénarios d'erreur
```gherkin
  Scenario: Avatar non disponible
    Given le chemin de l'avatar configuré dans la démo est invalide (404)
    When la page profil se charge
    Then un avatar de substitution (initiales ou icône générique) est affiché à la place
    And aucune erreur 404 n'est propagée visuellement à l'utilisateur

  Scenario: Tentative de soumission d'un formulaire modal avec champ obligatoire vide
    Given la modal d'édition des informations est ouverte
    When l'utilisateur efface le champ Email et clique sur "Sauvegarder"
    Then le champ Email affiche un message d'erreur de validation "Ce champ est obligatoire"
    And la modal reste ouverte sans soumettre les données
```

## INVEST
- **Independent :** N'implique pas de système d'authentification réel ; les données sont fictives dans la démo.
- **Negotiable :** Le nombre de champs de la modal et la structure des sections peuvent être ajustés selon retour UX.
- **Valuable :** La page profil est un élément standard attendu dans tout thème admin ; sa présence dans la démo est un argument commercial du bundle.
- **Estimable :** Assemblage de composants existants (Modal US-011, Avatar US-009, Form US-014) avec deux modals Stimulus ; estimé 5 points.
- **Small :** Une seule page démo, deux modals, trois composants bundle réutilisables à créer.
- **Testable :** Ouverture/fermeture des modals, focus trap, rendu dark/responsive, vérifiables par tests fonctionnels.

## Dépendances
- **Dépend de :** US-009 (avatars/badges), US-011 (modals), US-014 (composants formulaire)
- **Bloque :** US-025 (audit a11y — page assemblée requise)

## Definition of Done
Voir `project-management/definition-of-done.md`.
