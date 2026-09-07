# US-013 — Composants Card, Media et Vidéo

**EPIC :** EPIC-003-composants-ui · **Statut :** 🔴 To Do · **Points :** 5 · **Priorité :** Should · **Sprint :** backlog

## Carte (Card)
> En tant que **P-002 — lead/designer UI**, je veux **des composants Twig Card générique, Media Card, grilles d'images (3 variantes) et vidéos responsives (4 variantes avec ratio)**, afin de **structurer les pages de contenu et les tableaux de bord avec des blocs visuels riches, cohérents et responsives**.

## Conversation
Ce ticket regroupe quatre familles de composants liés au contenu visuel, tous statiques (aucun Stimulus). **Card générique** (`ComponentCard`) : conteneur avec props `title` (string optionnel), `subtitle` (string optionnel), `padding` (sm|md|lg, défaut md), `shadow` (bool, défaut true), `border` (bool, défaut true) ; slots Twig `header`, `body`, `footer`. Référence : `Tools/sources/tailadmin-laravel-main/resources/views/components/common/component-card.blade.php`. **Media Card** : extension de Card avec une image ou vidéo en header, props `mediaSrc`, `mediaAlt`, `mediaAspect` (16/9|4/3|1/1). **Grid Images** (3 variantes) : `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/grid-image/image-01.html` à `image-03.html` — grid mono-image pleine largeur, grid 2 colonnes, grid masonry 3 colonnes ; implémentées comme Twig Components avec props `images` (tableau d'objets `{src, alt, caption?}`), `columns` (auto-détecté selon la variante). **Vidéo responsive** (4 variantes) : `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/video/video-01.html` à `video-04.html` — embed YouTube/Vimeo responsif via `aspect-ratio` Tailwind CSS v4, lecteur HTML5 natif, placeholder lazy-load, vidéo en pleine largeur. Référence Laravel : `Tools/sources/tailadmin-laravel-main/resources/views/components/ui/youtube-embed.blade.php`. Accessibilité : `aria-label` sur les iframes vidéo, `loading="lazy"` sur les images, `alt` obligatoire. Dark mode via `dark:` sur les wrappers. Livraison dans le bundle `TailsAdmin` ; démo dans `templates/demo/components/cards-media.html.twig` et `templates/demo/components/media-video.html.twig`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Affichage d'une Card générique
  Scenario: Card avec titre, corps et footer
    Given le développeur intègre une Card avec title="Statistiques" et les blocks body et footer remplis
    When la page est rendue
    Then un conteneur avec le titre "Statistiques" est visible
    And le corps et le footer sont correctement positionnés
    And la Card a une ombre et une bordure selon les props par défaut (shadow="true", border="true")
    And le composant est responsive (largeur 100% de son conteneur parent)
```
### Scénarios alternatifs
```gherkin
  Scenario: Embed vidéo YouTube responsif avec ratio 16/9
    Given le développeur intègre <twig:TailsAdmin:VideoEmbed src="https://www.youtube.com/embed/dQw4w9WgXcQ" label="Présentation produit" ratio="16/9" />
    When la page est rendue
    Then une iframe YouTube est affichée avec les attributs allowfullscreen et loading="lazy"
    And aria-label="Présentation produit" est présent sur l'iframe
    And le conteneur respecte le ratio 16/9 quelle que soit la largeur de l'écran (Tailwind aspect-video)

  Scenario: Grid images 2 colonnes
    Given le développeur intègre le composant GridImage variante 2 colonnes avec 4 images
    When la page est rendue sur écran large (≥ 1024px)
    Then les 4 images sont disposées en 2 colonnes égales avec gap entre elles
    And chaque image porte un attribut alt non vide
    When la page est affichée sur mobile (< 768px)
    Then les images passent en colonne unique (responsive)

  Scenario: Card sans ombre ni bordure
    Given le développeur intègre <twig:TailsAdmin:Card title="Info" shadow="false" border="false" />
    When la page est rendue
    Then le conteneur est affiché sans box-shadow ni border visible
```
### Scénarios d'erreur
```gherkin
  Scenario: Image sans attribut alt dans la grid
    Given le développeur passe un objet image sans clé "alt" dans le tableau images
    When le composant GridImage est rendu en mode debug Symfony
    Then une exception LogicException est levée : "Chaque image doit posséder un attribut alt non vide (accessibilité)."

  Scenario: URL vidéo non sécurisée (HTTP)
    Given le développeur intègre VideoEmbed avec src="http://..." (HTTP non HTTPS)
    When le composant est rendu en mode debug
    Then une exception LogicException est levée : "L'URL de l'embed vidéo doit utiliser HTTPS."
```

## INVEST
- **Independent :** Les composants Card, GridImage et VideoEmbed sont indépendants entre eux et des autres US en cours ; ils partagent uniquement les tokens Tailwind.
- **Negotiable :** La Media Card peut être déprioritisée vers un sprint ultérieur si 5 pts s'avèrent trop larges pour le sprint cible ; la grid masonry (variante 3) est également négociable.
- **Valuable :** Les dashboards et pages de contenu sont les surfaces les plus visibles du bundle ; des composants Card et média soignés démontrent la valeur du bundle à l'intégrateur (P-001) et au designer (P-002).
- **Estimable :** 4 familles statiques, pas de JS, mais pluralité de variantes et de props → 5 points.
- **Small :** 5 points ; les composants de tableau (data grid) sont exclus (autre US).
- **Testable :** Critères couvrent card générique, embed vidéo responsif, grid images responsive, dark mode, et validations de prop.

## Dépendances
- **Dépend de :** US-002 (Tailwind/tokens), US-004 (layout de base)
- **Bloque :** US-021

## Definition of Done
Voir `project-management/definition-of-done.md`.
