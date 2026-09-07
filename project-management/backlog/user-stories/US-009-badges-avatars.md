# US-009 — Composants Badge et Avatar

**EPIC :** EPIC-003-composants-ui · **Statut :** 🔴 To Do · **Points :** 3 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — développeur intégrateur**, je veux **des composants Twig Badge (6 variantes chromatiques) et Avatar (4 variantes avec indicateur de statut en ligne)**, afin de **enrichir les tableaux de bord et listes avec des étiquettes et des représentations d'utilisateurs accessibles et cohérentes**.

## Conversation
Le composant **Badge** expose 6 déclinaisons issues de `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/badge/badge-01.html` à `badge-06.html` : primary, secondary, success, warning, error, info. Props : `variant` (enum), `size` (sm|md|lg, défaut md), `label` (string). Chaque variante mappe vers un jeu de classes Tailwind CSS v4 défini dans la classe `BadgeComponent`. Le composant **Avatar** couvre 4 variantes (`avatar-01.html` à `avatar-04.html`) : image seule, initiales, groupe d'avatars, avatar avec badge de statut. Props : `src` (URL image, nullable), `alt` (string), `initials` (string, fallback si src null), `size` (xs|sm|md|lg|xl), `status` (online|away|busy|offline, nullable). L'indicateur de statut en ligne est un pastille positionnée en `absolute` en bas-droite via Tailwind. La référence Laravel est `Tools/sources/tailadmin-laravel-main/resources/views/components/ui/` (badge.blade.php, avatar.blade.php). Les deux composants sont purement statiques, sans Stimulus. Accessibilité : `aria-label` sur les badges de statut, `role="img"` sur les avatars à initiales. Dark mode géré via classes `dark:`. Livraison dans le bundle `TailsAdmin` ; démo dans `templates/demo/components/badges-avatars.html.twig`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Affichage d'un badge de succès
  Scenario: Rendu d'un badge success taille md
    Given le développeur intègre <twig:TailsAdmin:Badge variant="success" label="Actif" />
    When la page est rendue
    Then un élément <span> avec les classes Tailwind success (vert) est visible
    And le texte "Actif" est contenu dans le span
    And l'élément est accessible (contraste AA minimum)
```
### Scénarios alternatifs
```gherkin
  Scenario: Avatar avec image et statut online
    Given le développeur intègre <twig:TailsAdmin:Avatar src="/img/user.jpg" alt="Jane Doe" status="online" size="md" />
    When la page est rendue
    Then une image est affichée avec alt="Jane Doe"
    And une pastille verte indiquant le statut en ligne est positionnée en bas-droite de l'avatar
    And la pastille porte aria-label="En ligne"

  Scenario: Avatar avec initiales (image absente)
    Given le développeur intègre <twig:TailsAdmin:Avatar initials="JD" size="lg" />
    When la page est rendue
    Then un cercle coloré affichant "JD" est rendu avec role="img" et aria-label="JD"
    And aucune balise <img> n'est présente dans le DOM

  Scenario: Badge taille small
    Given le développeur intègre <twig:TailsAdmin:Badge variant="warning" label="En attente" size="sm" />
    When la page est rendue
    Then le badge est affiché avec des classes de taille réduite (text-xs, px-2, py-0.5)
```
### Scénarios d'erreur
```gherkin
  Scenario: Variant Badge invalide
    Given le développeur passe variant="purple" (non supporté)
    When le composant est rendu en mode debug Symfony
    Then une exception LogicException est levée : "Variante de badge invalide : purple."

  Scenario: Avatar sans src ni initiales
    Given le développeur intègre <twig:TailsAdmin:Avatar size="md" /> sans src ni initials
    When le composant est rendu en mode debug
    Then une exception LogicException est levée : "Avatar requiert au moins src ou initials."
```

## INVEST
- **Independent :** Badge et Avatar n'ont aucune dépendance inter-composants (hors tokens Tailwind US-002) ; ils peuvent être développés en parallèle.
- **Negotiable :** Le groupe d'avatars empilés (avatar-04) peut être extrait en sous-composant séparé si la complexité s'avère supérieure à l'estimation.
- **Valuable :** Les badges structurent l'information dans tous les tableaux et cartes ; les avatars humanisent les listes d'utilisateurs et les commentaires.
- **Estimable :** 2 composants statiques, props simples, pas de JavaScript → 3 points.
- **Small :** 3 points, périmètre borné ; groupe d'avatars inclus car déjà dans les sources.
- **Testable :** Critères couvrent image/initiales, statuts, tailles, variantes chromatiques, et cas d'erreur.

## Dépendances
- **Dépend de :** US-002 (Tailwind/tokens), US-004 (layout de base)
- **Bloque :** US-021, US-022

## Definition of Done
Voir `project-management/definition-of-done.md`.
