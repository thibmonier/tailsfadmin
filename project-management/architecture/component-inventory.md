# Inventaire des composants — TailsfadminBundle

**Version :** 1.0.0  
**Date :** 2026-09-07  
**Auteur :** Thibaut Monier  
**Périmètre :** Mapping source TailAdmin (HTML + Blade) → Twig Components Symfony

---

## 1. Convention de nommage & namespace

### Schéma retenu : préfixe `tsf`

Le namespace Twig est `Tailsfadmin` (snake-case du bundle), ce qui donne les balises de la forme :

```twig
<twig:Tailsfadmin:Alert variant="success" title="OK" />
<twig:Tailsfadmin:Ui:Badge color="primary" />
<twig:Tailsfadmin:Layout:Sidebar />
```

Pour la fluidité dans les templates, le préfixe court **`tsf`** est enregistré comme alias de `Tailsfadmin` dans le fichier de configuration du bundle, ce qui permet d'écrire :

```twig
<twig:tsf:Alert variant="success" />
<twig:tsf:Ui:Badge color="primary" />
<twig:tsf:Layout:Sidebar />
```

**Justification :** `tsf` (3 lettres, sans collision connue avec Symfony UX ou TailwindUI) offre la brièveté d'un préfixe BSS-style tout en restant lisible et traçable jusqu'au nom du bundle ; les sous-espaces `Ui`, `Layout`, `Form`, `Dashboard`, `Profile` et `Auth` reprennent les familles TailAdmin/Blade pour faciliter la navigation.

### Distinction présentational / interactif

| Type | Définition | Implémentation |
|------|-----------|----------------|
| **Présentationnel** | Rendu HTML pur, aucun état JS requis | Classe PHP `AbstractTwigComponent` (attributs uniquement) |
| **Interactif** | État ou comportement côté client (menu, modale, graphe…) | Classe PHP `AbstractTwigComponent` + contrôleur Stimulus associé via `data-controller` |

---

## 2. Table de mapping des composants

> **Légende :** P = Présentationnel · I = Interactif · SC = Stimulus Controller

### 2.1 Alerts

| Source (Tools/sources/…) | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------------------------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/alert/alert-success.html` | `<twig:tsf:Alert>` | I | `alert-dismiss` | `variant` (success\|error\|warning\|info), `title`, `message`, `dismissible`, `link_href`, `link_text` | US-008 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/alert/alert-error.html` | _(variante de `Alert`)_ | I | `alert-dismiss` | `variant="error"` | US-008 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/alert/alert-warning.html` | _(variante de `Alert`)_ | I | `alert-dismiss` | `variant="warning"` | US-008 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/alert/alert-info.html` | _(variante de `Alert`)_ | I | `alert-dismiss` | `variant="info"` | US-008 |
| `tailadmin-laravel-main/resources/views/components/ui/alert.blade.php` | `<twig:tsf:Alert>` | I | `alert-dismiss` | `variant`, `title`, `message`, `show_link`, `link_href`, `link_text` | US-008 |

### 2.2 Avatars

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/avatar/avatar-01.html` | `<twig:tsf:Ui:Avatar>` | P | — | `src`, `alt`, `size` (xsmall\|small\|medium\|large\|xlarge\|xxlarge), `status` (none\|online\|offline\|busy) | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/avatar/avatar-02.html` | `<twig:tsf:Ui:AvatarGroup>` | P | — | `avatars[]` (src, alt, size), `max` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/avatar/avatar-03.html` | _(variante Avatar avec badge statut)_ | P | — | `src`, `status` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/avatar/avatar-04.html` | _(variante Avatar initiales)_ | P | — | `initials`, `size`, `color` | US-009 |
| `tailadmin-laravel-main/resources/views/components/ui/avatar.blade.php` | `<twig:tsf:Ui:Avatar>` | P | — | `src`, `alt`, `size`, `status` | US-009 |

### 2.3 Badges

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/badge/badge-01.html` | `<twig:tsf:Ui:Badge>` | P | — | `variant` (light\|solid), `color` (primary\|success\|error\|warning\|info\|light\|dark), `size` (sm\|md), `start_icon`, `end_icon` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/badge/badge-02.html` | _(variante solid de `Badge`)_ | P | — | `variant="solid"` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/badge/badge-03.html` | _(variante avec icône gauche)_ | P | — | `start_icon` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/badge/badge-04.html` | _(variante avec icône droite)_ | P | — | `end_icon` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/badge/badge-05.html` | `<twig:tsf:Ui:BadgeDot>` | P | — | `color`, `size`, `label` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/badge/badge-06.html` | _(variante numérique)_ | P | — | `count`, `color` | US-009 |
| `tailadmin-laravel-main/resources/views/components/ui/badge.blade.php` | `<twig:tsf:Ui:Badge>` | P | — | `variant`, `size`, `color`, `start_icon`, `end_icon` | US-009 |

### 2.4 Buttons

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/buttons/button-01.html` | `<twig:tsf:Ui:Button>` | P | — | `variant` (primary\|outline\|ghost\|destructive), `size` (sm\|md), `disabled`, `type`, `start_icon`, `end_icon` | US-010 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/buttons/button-02.html` | _(variante outline)_ | P | — | `variant="outline"` | US-010 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/buttons/button-03.html` | _(variante avec icône gauche)_ | P | — | `start_icon` | US-010 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/buttons/button-04.html` | _(variante avec icône droite)_ | P | — | `end_icon` | US-010 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/buttons/button-05.html` | `<twig:tsf:Ui:ButtonGroup>` | P | — | `buttons[]` (label, variant, disabled) | US-010 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/buttons/button-06.html` | _(variante icon-only / pill)_ | P | — | `icon`, `rounded="full"` | US-010 |
| `tailadmin-laravel-main/resources/views/components/ui/button.blade.php` | `<twig:tsf:Ui:Button>` | P | — | `variant`, `size`, `disabled`, `start_icon`, `end_icon`, `class` | US-010 |

### 2.5 Cards / Media

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/media-card.html` | `<twig:tsf:Dashboard:MediaCard>` | I | `dropdown` | `title`, `search` (bool), `items[]` (src, name, size, date, type), `dropdown_items[]` | US-013 |
| `tailadmin-laravel-main/resources/views/components/common/component-card.blade.php` | `<twig:tsf:Ui:ComponentCard>` | P | — | `title`, `description` | US-013 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/common-social-links.html` | `<twig:tsf:Profile:SocialLinks>` | P | — | `links[]` (network, url, icon)` | US-022 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/common-grid-shape.html` | `<twig:tsf:Ui:GridShape>` | P | — | — (décoratif SVG) | US-004 |

### 2.6 Grid Images

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/grid-image/image-01.html` | `<twig:tsf:Ui:ImageGrid>` | P | — | `images[]` (src, alt, caption), `columns` (1\|2\|3\|4) | US-013 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/grid-image/image-02.html` | _(variante masonry)_ | P | — | `images[]`, `layout="masonry"` | US-013 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/grid-image/image-03.html` | _(variante featured + grille)_ | P | — | `images[]`, `layout="featured"` | US-013 |

### 2.7 Videos

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/video/video-01.html` | `<twig:tsf:Ui:VideoEmbed>` | P | — | `src` (URL YouTube/Vimeo ou chemin), `title`, `aspect` (16:9\|4:3\|1:1) | US-013 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/video/video-02.html` | _(variante avec vignette + play overlay)_ | I | `video-player` | `thumbnail`, `src`, `title` | US-013 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/video/video-03.html` | _(variante liste de vidéos)_ | P | — | `videos[]` (src, title, duration) | US-013 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/video/video-04.html` | _(variante avec description)_ | P | — | `src`, `title`, `description` | US-013 |
| `tailadmin-laravel-main/resources/views/components/ui/youtube-embed.blade.php` | `<twig:tsf:Ui:VideoEmbed>` | P | — | `src`, `title`, `aspect` | US-013 |

### 2.8 Modals / Overlay

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-laravel-main/resources/views/components/ui/modal.blade.php` | `<twig:tsf:Ui:Modal>` | I | `modal` | `is_open`, `show_close_button`, `size` (sm\|md\|lg\|xl), `title` | US-011 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/overlay.html` | `<twig:tsf:Ui:Overlay>` | I | `modal` | `opacity` | US-011 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/calendar-event-modal.html` | `<twig:tsf:Dashboard:CalendarEventModal>` | I | `modal`, `calendar` | `is_open`, `event_title`, `event_start`, `event_end`, `event_level` | US-020 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/profile/profile-info-modal.html` | `<twig:tsf:Profile:EditInfoModal>` | I | `modal` | `is_open`, `user` (objet) | US-022 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/profile/profile-address-modal.html` | `<twig:tsf:Profile:EditAddressModal>` | I | `modal` | `is_open`, `address` (objet) | US-022 |

### 2.9 Dropdowns

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-laravel-main/resources/views/components/common/dropdown-menu.blade.php` | `<twig:tsf:Ui:DropdownMenu>` | I | `dropdown` | `items[]` (label, url, icon), `trigger_icon` | US-012 |
| `tailadmin-laravel-main/resources/views/components/common/table-dropdown.blade.php` | `<twig:tsf:Ui:TableDropdown>` | I | `dropdown` | `items[]`, `align` (left\|right) | US-012 |
| `tailadmin-laravel-main/resources/views/components/header/notification-dropdown.blade.php` | `<twig:tsf:Layout:NotificationDropdown>` | I | `dropdown` | `notifications[]` (title, message, time, read), `notifying` | US-007 |
| `tailadmin-laravel-main/resources/views/components/header/user-dropdown.blade.php` | `<twig:tsf:Layout:UserDropdown>` | I | `dropdown` | `user` (name, avatar, role), `languages[]`, `menu_items[]` | US-007 |

### 2.10 Breadcrumb

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/breadcrumb.html` | `<twig:tsf:Layout:Breadcrumb>` | P | — | `page_title`, `crumbs[]` (label, url) | US-004 |
| `tailadmin-laravel-main/resources/views/components/common/page-breadcrumb.blade.php` | `<twig:tsf:Layout:Breadcrumb>` | P | — | `page_title`, `crumbs[]` | US-004 |

### 2.11 Sidebar

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/sidebar.html` | `<twig:tsf:Layout:Sidebar>` | I | `sidebar` | `logo_src`, `logo_alt`, `menu[]` (label, icon, url, children[], badge), `current_route`, `collapsed` | US-006 |

### 2.12 Header

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/header.html` | `<twig:tsf:Layout:Header>` | I | `sidebar`, `dropdown`, `theme` | `page_title`, `user`, `notifications[]`, `show_search` | US-007 |
| `tailadmin-laravel-main/resources/views/components/common/theme-toggle.blade.php` | `<twig:tsf:Ui:ThemeToggle>` | I | `theme` | — | US-005 |

### 2.13 Preloader

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/preloader.html` | `<twig:tsf:Layout:Preloader>` | I | `preloader` | `color` (brand\|gray\|white), `delay_ms` | US-004 |
| `tailadmin-laravel-main/resources/views/components/common/preloader.blade.php` | `<twig:tsf:Layout:Preloader>` | I | `preloader` | `color`, `delay_ms` | US-004 |

### 2.14 Tables

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/table/table-01.html` | `<twig:tsf:Ui:Table>` | I | `dropdown` | `columns[]` (label, key, sortable), `rows[]`, `show_checkbox`, `bordered`, `striped` | US-017 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/table/table-06.html` | _(variante avec pagination)_ | I | `dropdown` | `columns[]`, `rows[]`, `paginator` | US-017 |
| `tailadmin-laravel-main/resources/views/components/tables/basic-tables/basic-tables-one.blade.php` | `<twig:tsf:Ui:Table>` | I | `dropdown` | `columns[]`, `rows[]`, `show_checkbox` | US-017 |
| `tailadmin-laravel-main/resources/views/components/tables/basic-tables/basic-tables-two.blade.php` | _(variante avec avatar dans cellule)_ | I | `dropdown` | `columns[]`, `rows[]` | US-017 |
| `tailadmin-laravel-main/resources/views/components/tables/basic-tables/basic-tables-three.blade.php` | _(variante minimale sans bordures)_ | P | — | `columns[]`, `rows[]` | US-017 |
| `tailadmin-laravel-main/resources/views/components/tables/basic-tables/basic-tables-four.blade.php` | _(variante avec status badges)_ | P | — | `columns[]`, `rows[]` | US-017 |
| `tailadmin-laravel-main/resources/views/components/tables/basic-tables/basic-tables-five.blade.php` | _(variante dark header)_ | P | — | `columns[]`, `rows[]` | US-017 |

### 2.15 Form elements

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-laravel-main/resources/views/components/form/form-elements/default-inputs.blade.php` | `<twig:tsf:Form:Input>` | P | — | `label`, `name`, `type`, `placeholder`, `value`, `required`, `disabled`, `error`, `helper` | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/form-elements/input-states.blade.php` | _(variante états : error/success)_ | P | — | `state` (error\|success\|default) | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/form-elements/input-group.blade.php` | `<twig:tsf:Form:InputGroup>` | P | — | `label`, `name`, `prefix`, `suffix`, `type`, `placeholder` | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/form-elements/text-area-inputs.blade.php` | `<twig:tsf:Form:Textarea>` | P | — | `label`, `name`, `rows`, `placeholder`, `value`, `required`, `error` | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/form-elements/select-inputs.blade.php` | `<twig:tsf:Form:Select>` | P | — | `label`, `name`, `options[]` (value, label), `selected`, `required`, `placeholder` | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/select/multiple-select.blade.php` | `<twig:tsf:Form:MultiSelect>` | I | `multi-select` | `label`, `name`, `options[]`, `selected[]`, `placeholder` | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/form-elements/checkbox-component.blade.php` | `<twig:tsf:Form:Checkbox>` | P | — | `label`, `name`, `checked`, `disabled`, `value` | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/form-elements/radio-buttons.blade.php` | `<twig:tsf:Form:Radio>` | P | — | `label`, `name`, `value`, `checked`, `disabled` | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/input/radio.blade.php` | _(variante radio minimaliste)_ | P | — | `name`, `value`, `checked` | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/form-elements/toggle-switch.blade.php` | `<twig:tsf:Form:Toggle>` | P | — | `label`, `name`, `checked`, `disabled`, `size` (sm\|md) | US-014 |
| `tailadmin-laravel-main/resources/views/components/form/form-elements/file-input-example.blade.php` | `<twig:tsf:Form:FileInput>` | P | — | `label`, `name`, `accept`, `multiple` | US-016 |

### 2.16 Datepicker

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/datepicker.html` | `<twig:tsf:Form:Datepicker>` | I | `datepicker` (flatpickr) | `name`, `id`, `mode` (single\|range\|multiple\|time), `default_date`, `date_format`, `placeholder`, `label` | US-015 |
| `tailadmin-laravel-main/resources/views/components/form/date-picker.blade.php` | `<twig:tsf:Form:Datepicker>` | I | `datepicker` (flatpickr) | `name`, `id`, `mode`, `default_date`, `date_format`, `placeholder`, `label` | US-015 |

### 2.17 Upload / Dropzone

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-laravel-main/resources/views/components/form/form-elements/dropzone.blade.php` | `<twig:tsf:Form:Dropzone>` | I | `dropzone` | `name`, `accept`, `max_size`, `max_files`, `label`, `description`, `preview` (bool) | US-016 |

### 2.18 Charts

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/chart/chart-01.html` | `<twig:tsf:Dashboard:LineChart>` (Monthly Sales) | I | `apexcharts` | `title`, `series[]` (name, data[]), `categories[]`, `height`, `dropdown_items[]` | US-018 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/chart/chart-02.html` | `<twig:tsf:Dashboard:BarChart>` | I | `apexcharts` | `title`, `series[]`, `categories[]`, `height`, `distributed` | US-018 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/chart/chart-03.html` | `<twig:tsf:Dashboard:DonutChart>` (Statistics) | I | `apexcharts` | `title`, `series[]` (label, value, color), `total`, `legend` (bool) | US-018 |
| `tailadmin-laravel-main/resources/views/components/ecommerce/statistics-chart.blade.php` | `<twig:tsf:Dashboard:StatisticsChart>` | I | `apexcharts` | `title`, `weekly_data[]`, `monthly_data[]` | US-018 |
| `tailadmin-laravel-main/resources/views/components/ecommerce/monthly-sale.blade.php` | `<twig:tsf:Dashboard:MonthlySaleChart>` | I | `apexcharts` | `title`, `months[]`, `revenue[]`, `orders[]` | US-021 |

### 2.19 Map

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/map-01.html` | `<twig:tsf:Dashboard:VectorMap>` | I | `vectormap` (jsvectormap) | `title`, `subtitle`, `countries[]` (code, value, label), `map_id` | US-019 |

### 2.20 Calendrier

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-laravel-main/resources/views/components/calender-area.blade.php` | `<twig:tsf:Dashboard:Calendar>` | I | `calendar` (FullCalendar) | `events[]` (title, start, end, level, color), `initial_view`, `locale` | US-020 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/upcoming-schedule.html` | `<twig:tsf:Dashboard:UpcomingSchedule>` | P | — | `title`, `events[]` (time, title, tag_color, description) | US-020 |

### 2.21 Dashboard — Métriques / Groupes

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/metric-group/metric-group-01.html` | `<twig:tsf:Dashboard:MetricGroup>` | P | — | `metrics[]` (icon, label, value, trend, trend_direction, percent) | US-021 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/top-card-group.html` | `<twig:tsf:Dashboard:TopCardGroup>` | I | `dropdown` | `title`, `tabs[]`, `rows[]` (label, value, progress, badge) | US-021 |
| `tailadmin-laravel-main/resources/views/components/ecommerce/ecommerce-metrics.blade.php` | `<twig:tsf:Dashboard:EcommerceMetrics>` | P | — | `customers`, `orders`, `orders_trend`, `customers_trend` | US-021 |
| `tailadmin-laravel-main/resources/views/components/ecommerce/monthly-target.blade.php` | `<twig:tsf:Dashboard:MonthlyTarget>` | I | `apexcharts`, `dropdown` | `title`, `target_percent`, `description` | US-021 |
| `tailadmin-laravel-main/resources/views/components/ecommerce/customer-demographic.blade.php` | `<twig:tsf:Dashboard:CustomerDemographic>` | I | `vectormap` | `title`, `countries[]` | US-021 |
| `tailadmin-laravel-main/resources/views/components/ecommerce/recent-orders.blade.php` | `<twig:tsf:Dashboard:RecentOrders>` | I | `dropdown` | `orders[]` (id, customer, product, date, status, amount) | US-021 |
| `tailadmin-free-tailwind-dashboard-template-main/src/partials/watchlist.html` | `<twig:tsf:Dashboard:Watchlist>` | I | `dropdown` | `title`, `items[]` (symbol, name, price, change, chart_data[])` | US-021 |

### 2.22 Profil

| Source | Composant Twig proposé | Type | Contrôleur Stimulus | Props principales | US |
|--------|------------------------|------|---------------------|-------------------|----|
| `tailadmin-laravel-main/resources/views/components/profile/profile-card.blade.php` | `<twig:tsf:Profile:ProfileCard>` | I | `modal` | `user` (avatar, name, role, location, socials[]), `show_edit_button` | US-022 |
| `tailadmin-laravel-main/resources/views/components/profile/personal-info-card.blade.php` | `<twig:tsf:Profile:PersonalInfoCard>` | I | `modal` | `user` (firstName, lastName, email, phone, bio, joined), `show_edit_button` | US-022 |
| `tailadmin-laravel-main/resources/views/components/profile/address-card.blade.php` | `<twig:tsf:Profile:AddressCard>` | I | `modal` | `address` (country, city, postal, street), `show_edit_button` | US-022 |

---

## 3. Table de mapping des pages

| Page HTML source | Route Symfony proposée | Template de démo | US couvrante |
|------------------|------------------------|-----------------|--------------|
| `tailadmin-free-tailwind-dashboard-template-main/src/index.html` | `GET /demo/dashboard` | `demo/dashboard/index.html.twig` | US-021 |
| `tailadmin-free-tailwind-dashboard-template-main/src/alerts.html` | `GET /demo/ui/alerts` | `demo/ui/alerts.html.twig` | US-008 |
| `tailadmin-free-tailwind-dashboard-template-main/src/avatars.html` | `GET /demo/ui/avatars` | `demo/ui/avatars.html.twig` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/badge.html` | `GET /demo/ui/badges` | `demo/ui/badges.html.twig` | US-009 |
| `tailadmin-free-tailwind-dashboard-template-main/src/buttons.html` | `GET /demo/ui/buttons` | `demo/ui/buttons.html.twig` | US-010 |
| `tailadmin-free-tailwind-dashboard-template-main/src/basic-tables.html` | `GET /demo/tables/basic` | `demo/tables/basic.html.twig` | US-017 |
| `tailadmin-free-tailwind-dashboard-template-main/src/form-elements.html` | `GET /demo/forms/elements` | `demo/forms/elements.html.twig` | US-014, US-015, US-016 |
| `tailadmin-free-tailwind-dashboard-template-main/src/calendar.html` | `GET /demo/calendar` | `demo/calendar/index.html.twig` | US-020 |
| `tailadmin-free-tailwind-dashboard-template-main/src/line-chart.html` | `GET /demo/charts/line` | `demo/charts/line.html.twig` | US-018 |
| `tailadmin-free-tailwind-dashboard-template-main/src/bar-chart.html` | `GET /demo/charts/bar` | `demo/charts/bar.html.twig` | US-018 |
| `tailadmin-free-tailwind-dashboard-template-main/src/images.html` | `GET /demo/ui/images` | `demo/ui/images.html.twig` | US-013 |
| `tailadmin-free-tailwind-dashboard-template-main/src/videos.html` | `GET /demo/ui/videos` | `demo/ui/videos.html.twig` | US-013 |
| `tailadmin-free-tailwind-dashboard-template-main/src/profile.html` | `GET /demo/profile` | `demo/profile/index.html.twig` | US-022 |
| `tailadmin-free-tailwind-dashboard-template-main/src/sidebar.html` | `GET /demo/layout/sidebar` | `demo/layout/sidebar.html.twig` | US-006 |
| `tailadmin-free-tailwind-dashboard-template-main/src/signin.html` | `GET /demo/auth/signin` | `demo/auth/signin.html.twig` | US-023 |
| `tailadmin-free-tailwind-dashboard-template-main/src/signup.html` | `GET /demo/auth/signup` | `demo/auth/signup.html.twig` | US-023 |
| `tailadmin-free-tailwind-dashboard-template-main/src/blank.html` | `GET /demo/blank` | `demo/blank.html.twig` | US-023 |
| `tailadmin-free-tailwind-dashboard-template-main/src/404.html` | `GET /demo/404` | `demo/error/404.html.twig` | US-023 |

---

## 4. Inventaire des contrôleurs Stimulus

> Les contrôleurs sont déclarés dans `assets/controllers/` du bundle.  
> Convention de nommage : `kebab-case` identique à la valeur `data-controller`.

| Nom du contrôleur | Rôle | Lib JS encapsulée | US |
|-------------------|------|-------------------|----|
| `theme` | Bascule dark mode / light mode ; écrit `dark` sur `<html>` et persiste le choix en `localStorage`. Déclenché par `<twig:tsf:Ui:ThemeToggle>`. | — | US-005 |
| `sidebar` | Ouverture/fermeture du panneau latéral, gestion du mode réduit (`collapsed`), overlay mobile, raccourci clavier `Escape`. Utilisé par `<twig:tsf:Layout:Sidebar>`. | — | US-006 |
| `dropdown` | Affichage/masquage d'un menu contextuel via clic ou tabulation ; fermeture au clic extérieur (`click.outside`) et à `Escape`. Utilisé par `DropdownMenu`, `TableDropdown`, `NotificationDropdown`, `UserDropdown`. | — | US-007, US-012 |
| `modal` | Gestion d'état `open/close` de la modale, gestion `overflow-hidden` sur `<body>`, focus trap, fermeture à `Escape`. Utilisé par `Modal`, `Overlay`, les modales profil et l'événement calendrier. | — | US-011 |
| `alert-dismiss` | Suppression douce (fade-out + `remove()`) d'une alerte à la suite d'un clic sur le bouton fermer. Utilisé par `<twig:tsf:Alert>` quand `dismissible=true`. | — | US-008 |
| `datepicker` | Intégration de Flatpickr : instanciation, configuration `mode/dateFormat/defaultDate`, dispatch de l'événement `date-change` Stimulus. Utilisé par `<twig:tsf:Form:Datepicker>`. | **Flatpickr** | US-015 |
| `dropzone` | Zone de dépôt de fichiers (drag & drop + click-to-browse) : validation du type, de la taille, prévisualisation des miniatures, émission de l'événement `files-changed`. Utilisé par `<twig:tsf:Form:Dropzone>`. | **Dropzone.js** (optionnel, fallback natif possible) | US-016 |
| `multi-select` | Select multiple avec recherche, checkboxes, sélection/déselection globale, gestion des tags dans le champ. Utilisé par `<twig:tsf:Form:MultiSelect>`. | — | US-014 |
| `apexcharts` | Instanciation et configuration d'ApexCharts depuis des attributs `data-*` (type, séries, catégories, couleurs, thème). Mise à jour réactive si les valeurs changent. Utilisé par `LineChart`, `BarChart`, `DonutChart`, `StatisticsChart`, `MonthlySaleChart`, `MonthlyTarget`. | **ApexCharts** | US-018 |
| `vectormap` | Rendu d'une carte vectorielle monde avec coloration par valeur par pays. Utilisé par `<twig:tsf:Dashboard:VectorMap>` et `CustomerDemographic`. | **jsvectormap** | US-019 |
| `calendar` | Initialisation de FullCalendar, chargement des événements depuis un attribut JSON ou une URL, ouverture du `CalendarEventModal` au clic d'un événement. Utilisé par `<twig:tsf:Dashboard:Calendar>`. | **FullCalendar** | US-020 |
| `preloader` | Masquage de l'overlay de chargement après l'événement `DOMContentLoaded` + délai configurable. Utilisé par `<twig:tsf:Layout:Preloader>`. | — | US-004 |
| `video-player` | Lecture d'une vidéo avec play overlay : affiche la vignette, bascule sur l'iframe YouTube/vidéo native au clic. Utilisé par la variante `video-02`. | — | US-013 |

---

## Récapitulatif chiffré

| Famille | Composants Twig distincts | dont interactifs |
|---------|--------------------------|-----------------|
| Alerts | 1 (+ 4 variantes via props) | 1 |
| Avatars | 2 | 0 |
| Badges | 2 | 0 |
| Buttons | 2 | 0 |
| Cards / Media | 3 | 1 |
| Grid Images | 1 (+ 3 variantes) | 0 |
| Videos | 2 | 1 |
| Modals / Overlay | 5 | 5 |
| Dropdowns | 4 | 4 |
| Breadcrumb | 1 | 0 |
| Sidebar | 1 | 1 |
| Header | 2 | 2 |
| Preloader | 1 | 1 |
| Tables | 1 (+ 5 variantes) | 2 |
| Form elements | 9 | 1 |
| Datepicker | 1 | 1 |
| Upload/Dropzone | 1 | 1 |
| Charts | 5 | 5 |
| Map | 1 | 1 |
| Calendar | 2 | 1 |
| Métriques/Dashboard | 6 | 4 |
| Profil | 4 | 3 |
| **Total** | **57** | **35** |

**Contrôleurs Stimulus à créer : 13**

---

*Dernière mise à jour : 2026-09-07*
