# ADR-002 : Structure — bundle réutilisable + application de démo

## Statut

**Accepté** (2026-09-07)

## Contexte

Le livrable est **double** (décision d'init) : un **bundle Symfony réutilisable** (le produit) et une **application de démo** qui le consomme et présente toutes les pages. Il faut une frontière nette pour garantir que le bundle est utilisable **hors** de la démo (exigence P-001/P-003, DoD §6).

## Décision

Adopter un **monorepo** avec séparation physique claire :

```
tailsfadmin/
├── src/                      # TailsfadminBundle (le produit)
│   ├── TailsfadminBundle.php # extends Symfony\Component\HttpKernel\Bundle\AbstractBundle
│   ├── DependencyInjection/  # extension + Configuration (config du MenuBuilder, options)
│   ├── Twig/                 # Components + Extension (is_active, icônes)
│   ├── Service/              # MenuBuilder, …
│   └── Resources/ (ou config/, templates/, assets/)
│       ├── templates/        # layout + composants (namespace Twig @Tailsfadmin)
│       └── assets/
│           ├── src/          # contrôleurs Stimulus (source)
│           ├── dist/         # contrôleurs compilés (distribués)
│           └── styles/       # CSS source (tokens, @import "tailwindcss")
├── demo/                     # Application de démo Symfony 8.1
│   ├── src/Controller/       # routes vers les pages
│   ├── templates/            # pages assemblant les composants du bundle
│   └── config/               # importmap.php, packages/*.yaml
├── tests/                    # tests du bundle
├── docs/                     # doc + ADR
└── composer.json             # définit le package du bundle (+ require-dev pour la démo)
```

- Le **bundle** est un `AbstractBundle` (config simplifiée, Symfony ≥ 6.1).
- La **démo** `require` le bundle via un `path` repository Composer (local) pendant le dev.
- Le bundle expose : templates Twig namespacés (`@Tailsfadmin`), Twig Components, assets (Stimulus + CSS), services (MenuBuilder, Twig Extension), et une configuration (`config/packages/tailsfadmin.yaml`).

## Alternatives considérées

| Option | ✅ | ❌ |
|--------|----|----|
| **Monorepo bundle + démo (choisi)** | Frontière nette, un seul dépôt, tests au même endroit, DX simple | Config Composer `path` à soigner |
| Deux dépôts séparés | Isolation forte | Sur-poids pour 1 dev ; friction de synchro doc/démo |
| App unique « thème intégré » (pas de bundle) | Le plus simple à démarrer | ❌ Tue la réutilisabilité — contraire au livrable double |

## Conséquences

### Positives
- La démo **prouve en continu** la consommabilité du bundle (dogfooding).
- Publication du bundle sur Packagist sans traîner le code de démo (via `.gitattributes export-ignore` / `composer` config).
- Tests du bundle isolés de la démo.

### Négatives / vigilance
- Discipline requise : **aucun** code réutilisable ne doit vivre dans `demo/` (contrôle en revue + DoD §6).
- Le mapping des chemins d'assets bundle → AssetMapper de la démo est traité en **ADR-003**.

## Références

- [Best practices for reusable bundles (Symfony Docs)](https://symfony.com/doc/current/bundles/best_practices.html)
- [Create a UX bundle (Symfony Docs)](https://symfony.com/doc/current/frontend/create_ux_bundle.html)
