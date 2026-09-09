# US-037 — Layouts d'exemple supplémentaires (6 variantes de shell)

**EPIC :** EPIC-010-composants-affichage-pages · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Could · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — développeur intégrateur**, je veux **six pages de démonstration illustrant des agencements de shell admin différents (position/état de la sidebar et du header, largeur de contenu)**, afin de **choisir et copier l'agencement le plus adapté à mon application sans réinventer le layout**.

## Conversation
Six variantes de shell portées depuis TailAdmin (réf. démo : [/layout-one](https://demo.tailadmin.com/layout-one) → [/layout-six](https://demo.tailadmin.com/layout-six)). Ce sont des **pages de la démo** (assemblage), pas de nouveaux composants bundle : elles **réutilisent** le layout `@Tailsfadmin/layout/admin.html.twig` et ses blocs (`sidebar`, `header`, `content`) en variant la configuration, afin de ne **pas dupliquer le shell**. Variantes envisagées (à préciser au raffinage avec P-002, en miroir de TailAdmin) :

1. Sidebar par défaut, extensible/rétractable (référence actuelle).
2. Sidebar réduite en icônes (mini-sidebar).
3. Navigation horizontale (menu dans le header, sans sidebar).
4. Contenu « boxed » (largeur contrainte et centrée).
5. Sidebar à droite (RTL-friendly / disposition inversée).
6. En-tête double niveau (barre supérieure + sous-barre d'actions).

Chaque variante est exposée sous une route de la démo (`/layouts/one`… `/layouts/six` ou équivalent) et listée dans une page d'index « Layouts ». Les différences se pilotent par des **options du layout** (blocs Twig surchargés, classes utilitaires, éventuel paramètre de configuration `tailsfadmin`) ; si une variante nécessite une bascule dynamique, elle réutilise les contrôleurs existants (`tailsfadmin--sidebar`), sans nouveau JS. Clair/dark, responsive, WCAG AA. Aucune dépendance JS tierce (pas de CDN).

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Galerie de layouts d'exemple
  Scenario: Accès à une variante de layout
    Given la démo expose une page d'index "Layouts" listant 6 variantes
    When l'utilisateur ouvre la variante "Navigation horizontale"
    Then la page répond en HTTP 200
    And le menu principal est rendu dans le header, sans sidebar latérale
    And l'en-tête et le contenu réutilisent les blocs du layout admin du bundle
```
### Scénarios alternatifs
```gherkin
  Scenario: Mini-sidebar (icônes)
    Given l'utilisateur ouvre la variante "Sidebar réduite"
    When la page est rendue
    Then la sidebar n'affiche que les icônes des items (labels masqués)
    And l'agencement reste accessible au clavier (labels exposés via aria-label ou title)

  Scenario: Contenu boxed
    Given l'utilisateur ouvre la variante "Boxed"
    When la page est rendue sur un écran large (≥ 1536px)
    Then la zone de contenu est contrainte à une largeur maximale et centrée

  Scenario: Cohérence dark mode
    Given une variante de layout est affichée
    When l'utilisateur bascule en dark mode
    Then les surfaces (sidebar, header, contenu) adoptent les tokens sombres
    And aucune inversion incorrecte des gris n'apparaît (garde v1)
```
### Scénarios d'erreur
```gherkin
  Scenario: Variante inconnue
    Given l'utilisateur demande une route de layout inexistante (/layouts/seven)
    When la requête est traitée
    Then une réponse HTTP 404 est renvoyée (aucune 500)

  Scenario: Absence de duplication du shell
    Given la revue de code de la variante "Sidebar à droite"
    When le reviewer inspecte le template
    Then la variante étend le layout admin du bundle (aucune copie du <head>, sidebar ou header dupliquée)
```

## INVEST
- **Independent :** pages de démo autonomes, isolées du code du bundle (assemblage) ; le layout admin est un prérequis stable.
- **Negotiable :** le choix précis des 6 variantes et leur pilotage (blocs vs config) est négociable au raffinage avec P-002.
- **Valuable :** accélère le démarrage : l'intégrateur copie l'agencement voulu au lieu de le construire.
- **Estimable :** 6 pages réutilisant le shell, avec quelques options de layout → 8 points.
- **Small :** borné à 6 variantes de démo ; pas de nouveau composant bundle, pas de refonte du layout.
- **Testable :** critères couvrant rendu 200, structure attendue par variante, dark mode, 404 et non-duplication.

## Dépendances
- **Dépend de :** US-004 (layout admin), US-006 (sidebar), US-007 (header), US-036 si une variante met en avant les nouveaux composants.
- **Bloque :** —

## Definition of Done
Voir `project-management/definition-of-done.md`.

---

## Notes
- Si une variante impose réellement une option de layout réutilisable (récurrence ≥ 3), l'extraire côté bundle plutôt que de la laisser dans la démo (règle des 3).
- Revue visuelle clair/dark par variante (P-002) ; test fonctionnel de rendu (200 + éléments clés) par variante.
