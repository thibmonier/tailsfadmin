# US-039 — Page « Integrations / API keys » (gestion des clés d'API)

**EPIC :** EPIC-010-composants-affichage-pages · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Could · **Sprint :** sprint-011

## Carte (Card)
> En tant que **P-004 — utilisateur final de l'admin** (via l'intégration faite par **P-001**), je veux **une page de gestion des clés d'API : lister mes clés (masquées), en générer/révoquer, et copier une clé en un clic**, afin de **configurer mes intégrations en toute sécurité depuis le back-office**.

## Conversation
Page de démo (assemblage) portée de TailAdmin (réf. [/api-keys](https://demo.tailadmin.com/api-keys)). Réutilise `tsf:Ui:Card`, la table, le modal (`tsf:Ui:Modal`), les boutons et badges existants. Elle présente :

- une **table des clés** : nom, préfixe/valeur **masquée** (ex. `sk_live_••••••••1234`), date de création, dernière utilisation, statut (badge actif/révoqué) ;
- une action **révéler/masquer** par ligne et **copier** dans le presse-papiers ;
- un bouton **« Générer une clé »** ouvrant un modal (nom + périmètre), affichant la clé complète **une seule fois** avec bouton copier et un avertissement ;
- une action **révoquer** (confirmation via modal ; **pas de dialogue navigateur bloquant** — cf. règles).

Interactivité : un **nouveau contrôleur Stimulus `tailsfadmin--clipboard`** (copie via `navigator.clipboard`, retour visuel « Copié ! », **sans dépendance externe**) et réutilisation du contrôleur `tailsfadmin--modal`. Le masquage se fait côté rendu (la démo n'expose que des clés factices). **Sécurité (démo) :** aucune vraie clé, données fictives ; en usage réel, la page hôte branche ses propres données. Clair/dark, responsive, WCAG AA, aucun CDN.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Gestion des clés d'API
  Scenario: Copier une clé dans le presse-papiers
    Given la page "api-keys" liste des clés dont la valeur est masquée
    When l'utilisateur clique sur "Copier" pour une clé
    Then la valeur de la clé est écrite dans le presse-papiers
    And un retour visuel "Copié !" s'affiche temporairement sur le bouton
```
### Scénarios alternatifs
```gherkin
  Scenario: Révéler puis masquer une clé
    Given une clé est affichée masquée (sk_live_••••••••1234)
    When l'utilisateur clique sur l'icône "révéler"
    Then la valeur complète de la clé (factice) est affichée
    And un nouveau clic la masque à nouveau

  Scenario: Générer une nouvelle clé
    Given l'utilisateur clique sur "Générer une clé"
    When il saisit un nom et valide dans le modal
    Then la clé complète est affichée une seule fois avec un bouton "Copier"
    And un avertissement indique qu'elle ne sera plus affichée ensuite

  Scenario: Révoquer une clé avec confirmation
    Given l'utilisateur clique sur "Révoquer" pour une clé active
    When un modal de confirmation s'ouvre et il confirme
    Then la clé passe au statut "révoquée" (badge) dans la table
    And aucun dialogue navigateur bloquant (alert/confirm) n'est utilisé
```
### Scénarios d'erreur
```gherkin
  Scenario: API presse-papiers indisponible
    Given le navigateur n'expose pas navigator.clipboard (contexte non sécurisé)
    When l'utilisateur clique sur "Copier"
    Then le contrôleur dégrade proprement (sélection du texte ou message) sans erreur JS non gérée

  Scenario: Génération sans nom
    Given le modal de génération est ouvert
    When l'utilisateur valide sans saisir de nom
    Then une erreur de validation est affichée et la clé n'est pas générée
```

## INVEST
- **Independent :** page de démo autonome, données factices ; réutilise des composants livrés.
- **Negotiable :** périmètre des actions (rotation, scopes détaillés) négociable ; le contrôleur clipboard peut servir ailleurs.
- **Valuable :** motif « réglages / intégrations » très courant en back-office.
- **Estimable :** page + 1 contrôleur Stimulus (clipboard) + réutilisation modal/table → 5 points.
- **Small :** borné à la page API keys ; pas de persistance ni backend réel (démo).
- **Testable :** critères couvrant copie, révéler/masquer, génération, révocation, dégradation clipboard et validation.

## Dépendances
- **Dépend de :** US-036 (si Tabs de réglages), composants Card/Table/Modal/Badge/Button existants ; contrôleur `tailsfadmin--modal`.
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.

---

## Notes
- Le contrôleur `tailsfadmin--clipboard` n'introduit aucune dépendance JS tierce.
- Ne jamais déclencher `alert()`/`confirm()` (confirmations via `tsf:Ui:Modal`). Données strictement factices dans la démo (aucun secret réel).
