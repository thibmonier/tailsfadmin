# US-028 — Distribution du thème CSS Tailwind (tokens + @source)

**EPIC :** EPIC-008-distribution-consommabilite · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Must · **Sprint :** Sprint 8

## Carte (Card)
> En tant que **P-003 — Consommateur du bundle**, je veux **importer le thème CSS de tailsfadmin (tokens de couleurs, dark mode, `@source` sur les templates du bundle) en une seule ligne dans mon `app.css`**, afin de **styliser mon app avec le design TailAdmin sans recopier `assets/styles/app.css`, et pouvoir surcharger les couleurs de marque**.

## Conversation
Le bundle contient déjà `assets/styles/app.css` (tokens, dark mode via `.dark`,
classes composants `.menu-item-*`, `.jvm-*`, focus-visible). Pour un consommateur,
il faut : (1) un point d'entrée CSS **importable** (`@import` d'un fichier distribué
par le bundle) qui apporte les tokens + les `@layer` composants ; (2) un `@source`
qui **scanne les templates du bundle** (sinon les utilitaires Tailwind employés dans
les composants ne sont pas générés — incident Sprint 2) ; (3) des **variables
personnalisables** (au minimum `--color-brand-*`) surchargeables par l'hôte après
l'import. La contrainte no-Node/Tailwind-standalone (ADR-001) est conservée.

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Thème CSS distribuable et personnalisable
  Scenario: Import du thème dans une app hôte
    Given une app hôte avec Tailwind v4 (binaire standalone)
    When le développeur ajoute un unique import du thème tailsfadmin dans son app.css
    Then les tokens (couleurs, dark, ombres) et les classes composants sont disponibles
    And les utilitaires employés par les templates du bundle sont bien générés (rendu correct)

  Scenario: Personnalisation de la marque
    Given le thème est importé
    When l'hôte redéfinit --color-brand-500 après l'import
    Then les composants (boutons, liens, menu actif) adoptent la nouvelle couleur

  Scenario: Dark mode
    Given le thème est importé et la classe .dark posée sur <html>
    Then les surfaces sombres s'appliquent (pas d'inversion des gris — cf. fix v1)
```

## INVEST
- **Independent** : porte sur le CSS, indépendant du JS (US-027).
- **Negotiable** : forme du point d'entrée (fichier importé vs config) à arbitrer.
- **Valuable** : le design est le cœur du produit ; il doit être réutilisable en 1 ligne.
- **Estimable** : extraire/organiser le CSS distribuable + `@source` + doc — 5 pts.
- **Small** : périmètre = distribution CSS + personnalisation minimale.
- **Testable** : rendu vérifié (smoke CSS + revue) dans l'app d'intégration (US-030).

## Dépendances
- **Dépend de :** bundle v1.0.0 (`assets/styles/app.css`).
- **Bloque :** US-029 (recipe), US-030 (test app vierge).

## Definition of Done
Voir `project-management/definition-of-done.md`.
