# Catalogue des composants tailsfadmin

Tous les composants sont des **Symfony UX Twig Components** préfixés `tsf:` et
s'invoquent en `<twig:tsf:… />`. Props scalaires en `attr="…"`, props
tableaux/booléens en `:attr="expression"`. Une galerie vivante est disponible
sur la route `/ui-kit` de la démo.

> **Convention** : le contenu textuel passe par le **slot par défaut** ou des
> **slots nommés** (`<twig:block name="…">`), selon le composant.

---

## Layout

| Composant | Rôle |
|-----------|------|
| `tsf:Layout:Sidebar` | Barre latérale (menu configuré via `tailsfadmin.yaml`, sous-menus repliables, RTL). |
| `tsf:Layout:Header` | En-tête (recherche Cmd+K, notifications, menu utilisateur, sélecteur de langue). |
| `tsf:Layout:Breadcrumb` | Fil d'Ariane. Prop : `pageName`. |

Les pages étendent `@Tailsfadmin/layout/admin.html.twig` (blocs `title`,
`breadcrumb`, `content`) ou `@Tailsfadmin/layout/auth.html.twig` (pages centrées).

---

## UI

### `tsf:Ui:Alert`
Props : `type` (`success|info|warning|error`), `title`, `dismissible` (bool).

### `tsf:Ui:Badge`
Props : `color` (`primary|success|error|warning|info|light|dark`), `size` (`sm|md`).
Libellé = slot par défaut.
```twig
<twig:tsf:Ui:Badge color="success">Livré</twig:tsf:Ui:Badge>
```

### `tsf:Ui:Avatar`
Props : `src` (vide → initiales calculées depuis `alt`), `alt`, `size`
(`xs|sm|md|lg|xl|2xl`), `status` (`''|online|offline`).

### `tsf:Ui:Button`
Props : `variant` (`primary|secondary|success|danger|ghost|link`), `size` (`sm|lg`),
`block` (bool, pleine largeur), `href` (rend un `<a>`), `type`, `loading` (bool),
`disabled` (bool), `iconStart`/`iconEnd` (SVG brut).
```twig
<twig:tsf:Ui:Button variant="primary" type="submit" :block="true">Enregistrer</twig:tsf:Ui:Button>
```

### `tsf:Ui:Card` · `MediaCard` · `GridImage`
`Card` : `title`, `subtitle`, `padding` (`sm|md|lg`), `shadow`/`border` (bool) ;
slots `header`/`body`/`footer`. `MediaCard` : `mediaSrc`, `mediaAlt` (requis),
`mediaAspect`. `GridImage` : `images` (`{src, alt requis, caption?}`), `columns`.

### `tsf:Ui:Dropdown`
Prop : `align` (`left|right`). Slots `trigger` (un `<button>`) et `menu`
(liens `role="menuitem"`). Câblé au contrôleur `tailsfadmin--dropdown`
(clavier, clic-extérieur, `aria-expanded` sur le bouton).

### `tsf:Ui:Modal`
Props : `size` (`sm|md|lg|xl`), `labelId` (= id du titre). Slots `trigger`
(auto-câblé), `header`, `body`, `footer`. Dialog accessible (focus trap, Échap,
restitution du focus). **Placez tout le `<form>` dans `body`** (les slots
`body`/`footer` sont des div séparés).

### `tsf:Ui:Table` · `TableAdvanced`
`Table` : `title`, `headers` (string[]), `rows` (string[][]), `striped`.
`TableAdvanced` : `title`, `rows` (`{name, role, project, status, budget}`) avec
Avatar + Badge intégrés.

### `tsf:Ui:Preloader`
Écran de chargement plein écran (inclus par défaut dans le layout admin).

### `tsf:Ui:ProgressBar`
Props : `value` (0–100, **borné** au rendu), `variant` (`brand|success|warning|error`),
`size` (`sm|md|lg`), `label`, `showValue`. Rend `role="progressbar"` avec
`aria-valuenow/min/max`. Statique (aucun JS).
```twig
<twig:tsf:Ui:ProgressBar :value="72" variant="success" showValue label="Upload" />
```

### `tsf:Ui:Ribbon`
Props : `text`, `variant` (`brand|success|warning|error`), `position`
(`top-left|top-right`), `shape` (`corner|rounded`). À placer dans un conteneur
`relative overflow-hidden`. Statique.
```twig
<div class="relative overflow-hidden rounded-2xl border p-6">
    <twig:tsf:Ui:Ribbon text="Nouveau" />
    …
</div>
```

### `tsf:Ui:Tabs`
Prop : `items` (`[{id,label,icon?}]`), `active` (id, défaut = 1er ; retombe sur le
1er si invalide), `variant` (`underline|segmented|pill|boxed`). Panneaux = **blocs
nommés par id**. Câblé au contrôleur `tailsfadmin--tabs` (pattern ARIA : clic +
flèches/Home/End, roving tabindex).

Variantes visuelles :
- `underline` (défaut) — soulignement de l'onglet actif ;
- `segmented` — conteneur gris pleine largeur + pastille blanche active (style « Default » TailAdmin) ;
- `pill` — version compacte (inline) du segmenté ;
- `boxed` — onglets en boîtes.

Icônes : renseigner `icon` sur un item (nom du registre `tsf_icon`) affiche l'icône
avant le libellé (ex. underline + icônes).
```twig
{# Onglets segmentés (Default) #}
<twig:tsf:Ui:Tabs variant="segmented" :items="[{id:'overview',label:'Overview'},{id:'analytics',label:'Analytics'}]">
    <twig:block name="overview">Vue d'ensemble</twig:block>
    <twig:block name="analytics">Analytique</twig:block>
</twig:tsf:Ui:Tabs>

{# Onglets soulignés avec icônes #}
<twig:tsf:Ui:Tabs :items="[{id:'ui',label:'Overview',icon:'ui-elements'},{id:'ch',label:'Analytics',icon:'charts'}]">
    <twig:block name="ui">Vue d'ensemble</twig:block>
    <twig:block name="ch">Analytique</twig:block>
</twig:tsf:Ui:Tabs>
```

---

## Form

Composants (génèrent un `id` auto si absent) : `Input`, `InputGroup` (slots
`prefix`/`suffix`), `Select` (`options: {value,label}[]`, `multiple`), `Textarea`
(`rows`), `Checkbox`, `Radio`, `Toggle`, `Datepicker` (flatpickr : `mode`,
`dateFormat`, `enableTime`), `Upload` (Dropzone : `url`, `maxFiles`, `acceptedFiles`).

Props communes : `label`, `name`, `value`, `placeholder`, `error` (état erreur),
`success` (bool), `disabled`, `required`.
```twig
<twig:tsf:Form:Input label="E-mail" name="email" type="email" :required="true" />
<twig:tsf:Form:Select label="Pays" name="country"
    :options="[{value: 'fr', label: 'France'}]" />
```

Un **form theme Symfony** (`@Tailsfadmin/form/theme.html.twig`, activé dans
`twig.yaml`) stylise aussi les formulaires Symfony natifs (`form_row`, etc.).

---

## Chart

| Composant | Lib | Props clés |
|-----------|-----|-----------|
| `tsf:Chart:Line` | ApexCharts | `series` (`{name,data}[]`), `categories`, `type` (`line|area`), `height`, `title` |
| `tsf:Chart:Bar` | ApexCharts | `series`, `categories`, `height`, `title` |
| `tsf:Chart:VectorMap` | jsvectormap | `markers` (`{name, coords:[lat,lng]}[]`), `map`, `height` |

```twig
<twig:tsf:Chart:Line :series="salesSeries" :categories="months" type="area" />
```

---

## Pièges connus (gotchas)

- **`tsf:Ui:Button` ne propage pas `{{ attributes }}`** : un `data-*`, `id` ou
  `aria-*` passé à `<twig:tsf:Ui:Button>` est **ignoré**. Pour attacher une action
  Stimulus (ex. fermer une modale), utilisez un `<button>` brut.
- **`_self.macro()` ne traverse pas un slot de composant** : une macro Twig
  appelée à l'intérieur d'un `<twig:block>` n'est pas résolue → utilisez
  `{% include %}`.
- **SVG passé en prop** : liez l'expression (`:iconStart="monSvg"`), n'utilisez
  **jamais** `iconStart="{{ monSvg }}"` (les guillemets du SVG cassent l'attribut).
- **Ajout d'un contrôleur Stimulus au bundle** : déclarez-le dans
  `assets/package.json` (`symfony.controllers`) **et** `controllers.json`, sinon
  StimulusBundle lève « controller does not exist in the package » (voir ADR-006).
- **Modale + `<form>`** : le formulaire complet (champs + boutons) doit tenir dans
  le slot `body` (les slots sont rendus dans des div distincts).
