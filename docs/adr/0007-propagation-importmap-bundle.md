# ADR-007 — Propagation de l'importmap du bundle vers l'application hôte

## Statut

Accepté (2026-09-08) — spike US-027 / T-027-01 (Sprint 8, distribution)

## Contexte

Le bundle tailsfadmin encapsule cinq bibliothèques JS tierces (ApexCharts,
jsvectormap, flatpickr, Dropzone, FullCalendar) sous forme de contrôleurs
Stimulus (ADR-004/006). Aujourd'hui, leurs entrées d'importmap (versions pinées)
vivent dans **`demo/importmap.php`** — un fichier de l'application de démo, exclu
du package distribué (`.gitattributes` `export-ignore`). Un projet tiers (hottwos)
qui installe le bundle **ne reçoit donc pas ces pins** et doit recopier
`demo/importmap.php` à la main — principal frein à l'adoption ciblé par EPIC-008.

Le `prepend()` du bundle sait déjà contribuer :
- au namespace Twig (`twig.paths`),
- au **path AssetMapper** des contrôleurs (`framework.asset_mapper.paths`, une **liste** mergeable),
- aux traductions (`framework.translator.paths`).

La question du spike : **le bundle peut-il, de la même façon, injecter des entrées
dans l'importmap de l'hôte via `prepend()` / `prependExtensionConfig()` ?**

### Preuve (symfony/asset-mapper v8.1.5, vendored)

- L'importmap est **un unique fichier PHP** (`importmap.php`), **lu et écrit** par
  `Symfony\Component\AssetMapper\ImportMap\ImportMapConfigReader`.
- Son emplacement vient de `framework.asset_mapper.importmap_path`, déclaré comme
  **`scalarNode`** dans `FrameworkExtension`/`Configuration` : **une seule valeur,
  non mergeable** (last-wins).
- Il n'existe **aucun nœud d'extension « importmap »** recevant des entrées via le
  container (contrairement à `asset_mapper.paths`, qui est une liste).

**Conclusion : un bundle ne peut pas contribuer d'entrées d'importmap par
`prepend()`.** Le mécanisme (a) envisagé dans US-027 est écarté factuellement.

## Décision

Le bundle fournit une **commande console dédiée** :

```bash
bin/console tailsfadmin:assets:install
```

Elle utilise l'**API publique** d'AssetMapper (`ImportMapManager` /
`ImportMapConfigReader` / `ImportMapEntries`) pour :

1. **Ajouter** dans l'`importmap.php` de l'hôte les entrées pinées manquantes,
   depuis une **source de vérité** distribuée par le bundle
   (`config/importmap-entries.php`) ;
2. **Déclencher le vendoring local** (téléchargement à l'installation, servi en
   local au runtime → **aucun CDN au runtime**, conforme ADR-004/006) ;
3. Rester **idempotente** : n'ajoute que ce qui manque, n'écrase pas une entrée
   déjà pinée par l'hôte (dark : voir « Conséquences »).

La recette Flex (US-029, stretch) ne fera que **rappeler / invoquer** cette
commande en post-install : la commande reste le mécanisme de référence, utilisable
**dès l'installation VCS/path** (avant publication Packagist), ce qui **débloque
le test d'intégration app vierge (US-030)** sans dépendre de Packagist.

## Alternatives considérées

- **(a) `prependExtensionConfig('importmap', …)`** — *rejeté (impossible)* :
  l'importmap n'est pas une config de container mergeable (preuve ci-dessus).
- **(c) Recette Flex écrivant les entrées** — *reporté (US-029, stretch)* :
  suppose Packagist + infra recipe, ne fonctionne pas en VCS/path immédiat, ne
  débloque pas US-030 tout de suite. Reste complémentaire (appellera la commande).
- **(d) Vendorer les libs tierces dans les assets du bundle + `asset_mapper.paths`**
  — *rejeté* : alourdit le bundle, duplique la maintenance des libs et va contre
  le modèle AssetMapper (résolution des *bare specifiers* toujours à déclarer).
- **(b bis) Fournir un `importmap.php` partiel + snippet à recopier** — *rejeté* :
  reproduit le copier-coller que l'EPIC vise à supprimer.

## Conséquences

### Positives
- Supprime la recopie de `demo/importmap.php` : `install` suffit (objectif EPIC-008).
- Fonctionne **avant Packagist** (VCS/path) → prérequis d'US-030 satisfait.
- Offline / sans CDN au runtime conservé (ADR-004/006).
- Source de vérité unique (`config/importmap-entries.php`), plus dans `demo/`.

### Négatives / vigilance
- Étape manuelle post-`composer require` tant que la recette Flex (US-029) n'est
  pas publiée — atténuée par un message post-install et la doc (US-027 T-027-07).
- **Idempotence & conflits de version** : si l'hôte a déjà piné une lib à une autre
  version, la commande **ne l'écrase pas silencieusement** ; elle signale le
  conflit et laisse le choix (option `--force` à préciser en implémentation).
- Le shim ESM FullCalendar et les entrées `path:` doivent être **livrés par le
  bundle** (sortis de `demo/`) et pinés en `path`, pas en `version`.

## Références
- ADR-003 (packaging assets & contrôleurs Stimulus), ADR-004 & ADR-006 (encapsulation libs JS via importmap).
- Spike : `symfony/asset-mapper` `ImportMapConfigReader`, `framework.asset_mapper.importmap_path` (scalarNode).
- US-027 (recette d'assets), US-029 (recette Flex), US-030 (test app vierge).
