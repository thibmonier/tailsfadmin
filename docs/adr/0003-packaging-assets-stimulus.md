# ADR-003 : Packaging des assets et des contrôleurs Stimulus du bundle

## Statut

**Accepté** (2026-09-07) — mécanique à valider en Sprint 1/2.

## Contexte

Le bundle doit distribuer, en plus de ses templates PHP/Twig : des **contrôleurs Stimulus** et du **CSS/JS**, consommables par l'AssetMapper de l'application hôte, **sans build Node côté consommateur**.

## Décision

Suivre la **convention officielle des « UX bundles »** de Symfony pour exposer les contrôleurs Stimulus, et l'auto-enregistrement d'assets d'AssetMapper pour le reste.

### Contrôleurs Stimulus (tiers, exposés par le bundle)
- Fichier **`package.json`** à la racine du package avec la clé **`symfony.controllers`** : chaque contrôleur pointe vers son fichier compilé dans **`assets/dist/`**, avec `fetch` (`eager`/`lazy`), `enabled`, `autoimport`.
- Ajouter le mot-clé **`symfony-ux`** dans `composer.json` (sinon Symfony Flex n'ira pas lire `package.json`).
- À l'installation, **Flex met à jour `assets/controllers.json`** de l'app hôte et la **StimulusBundle** enregistre automatiquement les contrôleurs (`data-controller="tailsfadmin--modal"`).
- Les **libs JS tierces** (ApexCharts, FullCalendar…) sont déclarées en `peerDependencies` + `importmap` du `package.json` du bundle, et vendorées côté app via `importmap:require`.

### Assets CSS / statiques
- Le dossier `assets/` du bundle est **auto-enregistré** dans les chemins AssetMapper (préfixe du bundle) → `asset('bundles/tailsfadmin/…')`.
- **CSS source** (tokens + `@import "tailwindcss"`) livré dans le bundle et **recompilé par l'app hôte** via `tailwind:build` (voir ADR-001) → conserve la personnalisation des tokens (P-002).
- Option future : fournir un **CSS précompilé** en fallback « zéro config » (non retenu par défaut).

### Templates & Twig Components
- Templates dans `templates/` du bundle → namespace **`@Tailsfadmin`**.
- Twig Components avec **préfixe `tsf`** et sous-espaces (`Ui / Layout / Form / Dashboard / Profile / Auth`), ex. `<twig:tsf:Ui:Alert>`, déclarés via la configuration `twig_component`. Le namespace de **templates** `@Tailsfadmin` (chemins de fichiers) est distinct du préfixe de **composant** `tsf`.

## Alternatives considérées

| Option | ✅ | ❌ |
|--------|----|----|
| **Convention UX `symfony.controllers` (choisi)** | Standard, auto-enregistrement Flex, DX native | Nécessite une étape de **compilation des contrôleurs** vers `dist/` (build de release, pas côté consommateur) |
| Contrôleurs à copier manuellement par le consommateur | Aucun packaging | ❌ DX médiocre, non idiomatique |
| Tout en CDN | Simple | ❌ Contraire à AssetMapper/importmap vendoré, offline, CSP |

## Conséquences

### Positives
- Installation « `composer require` → contrôleurs disponibles » (DX P-001).
- CSS personnalisable (tokens) tout en restant packagé.
- Compatible CSP / offline (assets vendorés, pas de CDN runtime).

### Négatives / vigilance
- **Étape de build des contrôleurs** `assets/src` → `assets/dist` au moment de la release (documentée en US-026 ; peut utiliser un petit build ou une livraison directe si les contrôleurs restent en ESM simple).
- Bien tester l'auto-enregistrement Flex sur une app hôte tierce (pas seulement la démo interne).
- **Caveat constaté (US-004)** : le `prepend()` du bundle pour le **chemin d'assets AssetMapper** ne survit pas toujours au merge de configuration côté app hôte. L'app consommatrice doit ajouter explicitement le chemin des contrôleurs du bundle dans son `asset_mapper.yaml` (`../assets/controllers → bundles/tailsfadmin`) et activer le contrôleur dans son `assets/controllers.json`. À documenter dans le guide d'installation (US-026). Le namespace **Twig** `@Tailsfadmin` et le préfixe de composant **`tsf`** (via `#[AsTwigComponent]`), eux, se propagent correctement par `prepend()`.

## Références

- [Create a UX bundle (Symfony Docs)](https://symfony.com/doc/current/frontend/create_ux_bundle.html)
- [StimulusBundle Documentation](https://symfony.com/bundles/StimulusBundle/current/index.html)
- [AssetMapper — bundle assets (Symfony Docs)](https://symfony.com/doc/current/frontend/asset_mapper.html)
