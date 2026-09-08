# Recette Flex — tailsfadmin (US-029, stretch)

Recette Symfony Flex qui configure le bundle **à l'installation**, pour démarrer
sans configuration manuelle. Elle automatise ce que le test d'intégration
(`tests/integration/apply-steps.sh`) fait à la main.

## Contenu

```
recipe/tailsfadmin/tailsfadmin-bundle/1.0/
├── manifest.json                         # enregistrement + copy-from-recipe + message post-install
└── config/packages/
    ├── tailsfadmin.yaml                   # config par défaut (menu minimal, locales fr/en/ar/es/de)
    └── tailsfadmin_assets.yaml            # path AssetMapper des contrôleurs (+ vendor-src)
```

Ce que la recette fait à `composer require tailsfadmin/tailsfadmin-bundle` :

1. **Enregistre le bundle** dans `config/bundles.php` (`manifest.bundles`).
2. **Copie** `config/packages/tailsfadmin.yaml` et `tailsfadmin_assets.yaml`.
3. **Affiche un message post-install** rappelant les 2 étapes assets (US-027 :
   `tailsfadmin:assets:install` ; US-028 : import du thème CSS + compilation).

> Le path AssetMapper est posé dans un fichier **séparé** (`tailsfadmin_assets.yaml`)
> plutôt qu'en modifiant votre `asset_mapper.yaml` : Symfony fusionne
> `framework.asset_mapper.paths` de tous les fichiers de `config/packages/`, donc
> rien de votre config n'est écrasé.

## Idempotence (T-029-03)

Flex, via `copy-from-recipe`, **n'écrase pas** un fichier existant sans confirmation :
une ré-installation ou une mise à jour ne remplace pas votre `tailsfadmin.yaml`
déjà personnalisé. La config assets est dans son propre fichier au nom unique,
donc additive et sans collision.

## Dégradation sans AssetMapper (T-029-02)

`tailsfadmin_assets.yaml` ne configure que `framework.asset_mapper.paths`. Si
l'application **n'utilise pas AssetMapper**, cette clé est inerte (aucune erreur) ;
le reste de la recette (bundle + `tailsfadmin.yaml`) reste valable. Le thème CSS
et les contrôleurs supposent toutefois AssetMapper : c'est le mode nominal du bundle.

## Canal de distribution (T-029-02)

La recette n'est **active** qu'une fois servie par un endpoint Flex :

- **Contribution publique — `symfony/recipes-contrib`** (recommandé, **après** la
  publication Packagist du bundle — US-031 T-031-05). Procédure :
  1. Fork de `github.com/symfony/recipes-contrib`.
  2. Copier `tailsfadmin/tailsfadmin-bundle/1.0/` (ce dossier) à la racine du fork.
  3. `composer validate` + vérifier le manifeste, ouvrir une PR (le CI de
     recipes-contrib teste l'application de la recette).
  4. Une fois mergée, tout `composer require tailsfadmin/tailsfadmin-bundle`
     l'applique automatiquement.

- **Endpoint privé (test avant Packagist)** : servir un index Flex et pointer
  l'app dessus —
  ```bash
  composer config extra.symfony.endpoint \
      https://api.github.com/repos/thibmonier/tailsfadmin/contents/recipe
  ```
  (structure d'endpoint Flex requise ; utile pour valider la recette avant la
  contribution publique).

## Vérification (T-029-04)

La validation de bout en bout (bundle enregistré, `tailsfadmin.yaml` généré,
message post-install) se fait via une app vierge **une fois le canal actif**
(Packagist + recipes-contrib). Tant que Packagist n'est pas publié, le test
d'intégration (`tests/integration/`) applique les mêmes étapes explicitement.
