# US-033 — Pages type applicatives (Settings, Pricing, Invoice, Kanban, Chat, File manager, Inbox)

**EPIC :** EPIC-009-pages-exemples · **Statut :** 🟢 Done · **Points :** 8 · **Priorité :** Should · **Sprint :** Sprint 9

## Carte (Card)
> En tant que **P-004 — Utilisateur admin**, je veux **des pages type courantes (paramètres, tarification, facture, tableau kanban, messagerie, gestionnaire de fichiers, boîte de réception)**, afin de **construire rapidement les écrans usuels d'un back-office**.

## Conversation
Pages fidèles à TailAdmin, assemblées via le bundle : **Settings** (onglets +
formulaires), **Pricing** (grille de plans), **Invoice** (facture imprimable),
**Kanban** (colonnes + cartes, drag & drop via contrôleur Stimulus si besoin),
**Chat** (liste conversations + fil), **File manager** (grille/liste + upload
Dropzone existant), **Inbox** (liste mails + lecture). Certaines nécessitent
possiblement 1-2 nouveaux composants/contrôleurs (ex. onglets `tsf:Ui:Tabs`,
kanban) — à créer dans le bundle uniquement si réutilisables. Clair+dark,
accessible, responsive. Interactivité en Stimulus (ADR-004), pas d'Alpine.

## Confirmation — Critères d'acceptation (Gherkin)
```gherkin
Feature: Pages type applicatives
  Scenario: Chaque page s'affiche et respecte le thème
    Given l'utilisateur ouvre l'une des pages type
    Then la page retourne 200, assemble des composants tsf, rendu clair+dark OK
    And 0 violation axe A/AA, navigation clavier fonctionnelle

  Scenario: Nouveau composant justifié
    Given un besoin d'onglets (Settings) réutilisable ailleurs
    Then un composant bundle tsf:Ui:Tabs accessible (role=tablist) est créé
    And il est documenté dans docs/components.md
```

## INVEST
- **Independent** : chaque page est livrable séparément (sous-lot possible).
- **Negotiable** : sous-ensemble de pages selon la capacité du sprint.
- **Valuable** : couvre les écrans les plus demandés d'un admin.
- **Estimable** : ~7 pages + éventuels composants — 8 pts (découpable).
- **Small** : par page ; l'US regroupe un thème cohérent.
- **Testable** : tests fonctionnels + E2E (interactions) + axe.

## Dépendances
- **Dépend de :** EPIC-008, composants existants (form, upload, table, modal, dropdown).
- **Bloque :** US-035 (scaffolding).

## Definition of Done
Voir `project-management/definition-of-done.md`.
