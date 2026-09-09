# US-036 — Composants d'affichage : Tabs, Progress bars, Ribbons

**EPIC :** EPIC-010-composants-affichage-pages · **Statut :** 🔴 To Do · **Points :** 8 · **Priorité :** Could · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — développeur intégrateur**, je veux **trois nouveaux composants Twig du bundle — `tsf:Ui:Tabs` (onglets), `tsf:Ui:ProgressBar` (jauge de progression) et `tsf:Ui:Ribbon` (ruban)** — fidèles à TailAdmin, clair/dark et accessibles, afin de **couvrir des motifs d'UI courants sans réécrire de HTML/CSS custom dans chaque application hôte**.

## Conversation
Trois composants d'affichage portés depuis `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/` (réf. démo : [/tabs](https://demo.tailadmin.com/tabs), [/progress-bar](https://demo.tailadmin.com/progress-bar), [/ribbons](https://demo.tailadmin.com/ribbons)). Conventions du bundle : namespace `Tailsfadmin\Twig\Components\Ui\`, préfixe Twig `tsf:`, dark mode via variantes `dark:`, **aucun CDN** (ADR-004/006).

- **`tsf:Ui:Tabs`** — le seul à comporter du JS. Props : `items` (liste `{id, label, icon?}`), `active` (id de l'onglet actif, défaut = 1ᵉʳ), `variant` (`underline`|`pill`|`boxed`, défaut `underline`). Slots de panneaux nommés par id d'onglet. Interactivité via un **nouveau contrôleur Stimulus `tailsfadmin--tabs`** (sans dépendance externe) : bascule `aria-selected`, `hidden` sur les panneaux, navigation clavier (flèches, Home/End) selon le pattern ARIA Tabs (`role="tablist"`/`tab`/`tabpanel`). Contrôleur ajouté à `assets/controllers/tabs_controller.js` + déclaré dans `package.json`/`assets/package.json` (garde US-019).
- **`tsf:Ui:ProgressBar`** — statique (aucun JS). Props : `value` (0–100), `variant` (`brand`|`success`|`warning`|`error`, défaut `brand`), `size` (`sm`|`md`|`lg`, défaut `md`), `label` (string, optionnel), `showValue` (bool, défaut false). Rend `role="progressbar"` avec `aria-valuenow/min/max`. Valeur bornée [0,100].
- **`tsf:Ui:Ribbon`** — statique. Props : `text` (string), `variant` (couleur, défaut `brand`), `position` (`top-left`|`top-right`, défaut `top-right`), `shape` (`corner`|`rounded`, défaut `corner`). S'enroule autour d'un conteneur positionné (`relative`).

Livraison : composants dans le bundle ; démo dans `demo/templates/` (pages `/components/tabs`, `/components/progress`, `/components/ribbons` ou une page regroupée) + entrée dans la galerie `/ui-kit`. Doc composants (`docs/components.md`) mise à jour.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Onglets interactifs
  Scenario: Bascule d'onglet au clic
    Given une page intègre <twig:tsf:Ui:Tabs :items="[{id:'a',label:'Profil'},{id:'b',label:'Sécurité'}]" active="a" />
    When l'utilisateur clique sur l'onglet "Sécurité"
    Then le panneau "b" devient visible et le panneau "a" reçoit l'attribut hidden
    And l'onglet "Sécurité" porte aria-selected="true" et reçoit le focus
```
### Scénarios alternatifs
```gherkin
  Scenario: Navigation clavier des onglets (pattern ARIA)
    Given le focus est sur l'onglet actif d'un tablist horizontal
    When l'utilisateur presse la touche Flèche droite
    Then l'onglet suivant devient actif et affiche son panneau
    And la touche Home réactive le premier onglet

  Scenario: Barre de progression avec valeur affichée
    Given une page intègre <twig:tsf:Ui:ProgressBar :value="72" variant="success" showValue="true" />
    When la page est rendue
    Then un élément role="progressbar" avec aria-valuenow="72" est présent
    And la portion remplie occupe 72% de la largeur avec la couleur success
    And le libellé "72%" est affiché

  Scenario: Ruban d'angle sur une carte
    Given une carte relative intègre <twig:tsf:Ui:Ribbon text="Nouveau" variant="brand" position="top-right" />
    When la page est rendue
    Then le ruban "Nouveau" est positionné dans le coin supérieur droit de la carte
    And le rendu reste correct en dark mode (contraste ≥ 4,5:1)
```
### Scénarios d'erreur
```gherkin
  Scenario: Valeur de progression hors bornes
    Given le développeur passe :value="140" à tsf:Ui:ProgressBar
    When le composant est rendu en mode debug Symfony
    Then une exception LogicException est levée : "La valeur d'une ProgressBar doit être comprise entre 0 et 100."

  Scenario: Onglet actif inexistant
    Given le développeur passe active="z" à tsf:Ui:Tabs alors qu'aucun item n'a l'id "z"
    When le composant est rendu en mode debug
    Then une exception LogicException est levée : "L'onglet actif « z » ne correspond à aucun item."
```

## INVEST
- **Independent :** trois composants autonomes, sans dépendance à des composants en cours ; tokens/layout stables (prérequis livrés).
- **Negotiable :** regroupement en une seule US négociable (pourrait se scinder si la capacité l'exige) ; variante `boxed` des tabs ajustable avec P-002.
- **Valuable :** motifs récurrents (onglets de réglages, jauges de dashboard, badges promo) mutualisés côté bundle.
- **Estimable :** 2 composants statiques + 1 composant avec contrôleur Stimulus (pattern ARIA Tabs) → 8 points.
- **Small :** borné à 3 composants ; drag/tri, tabs verticaux animés et rubans SVG complexes exclus (YAGNI).
- **Testable :** critères couvrant rendu, interactivité clavier, dark mode, accessibilité et erreurs de props.

## Dépendances
- **Dépend de :** US-002 (Tailwind/tokens), US-004 (layout), US-010 (conventions composants) ; garde d'auto-enregistrement Stimulus (US-019) pour le contrôleur `tabs`.
- **Bloque :** US-037, US-038, US-039, US-040 (pages qui réutilisent ces composants).

## Definition of Done
Voir `project-management/definition-of-done.md`.

---

## Notes
- Le contrôleur `tailsfadmin--tabs` n'introduit **aucune** dépendance JS tierce (pas de `tailsfadmin:assets:install` requis pour cette US).
- Revue visuelle clair/dark obligatoire (P-002) ; tests fonctionnels de rendu + test e2e du montage des onglets.
