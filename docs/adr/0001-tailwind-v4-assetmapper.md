# ADR-001 : Intégration de Tailwind CSS v4 avec AssetMapper

## Statut

**Accepté et validé** (2026-09-07) — POC Sprint 1 (US-002) **concluant**.

### Résultat du POC (T-002-07)
- **Binaire Tailwind v4 : `v4.3.3`** épinglé dans `demo/config/packages/symfonycasts_tailwind.yaml` (`binary_version: v4.3.3`). Build en ~70 ms, **sans Node.js**.
- **CSS source du bundle** : `assets/styles/app.css` (`@import "tailwindcss"`, `@plugin "@tailwindcss/forms"`, `@custom-variant dark (&:is(.dark *))`, `@theme{}` tokens TailAdmin, overrides `.dark{}`).
- **Consommation par la démo** : `demo/assets/styles/app.css` fait `@import "../../../assets/styles/app.css"` (entrée Tailwind). Le CSS compilé (`public/assets/styles/app-*.css`) contient bien `.dark` et les utilitaires (`bg-brand-500`).
- **⚠️ Décision d'intégration clé** : le **CSS source du bundle n'est PAS exposé via AssetMapper** (les directives Tailwind brutes `@import "tailwindcss"` cassent la résolution AssetMapper). Il est **consommé directement par le CLI Tailwind au niveau du filesystem**. Seuls les **assets compilés** du bundle (contrôleurs Stimulus `dist/`) seront exposés via AssetMapper sous `bundles/tailsfadmin/…` (voir ADR-003, US-005+).
- Vérifié : `tailwind:build` OK, `asset-map:compile` OK, `.dark` présent dans le compilé, tests fonctionnels 5/5, PHPStan max 0 erreur.

## Contexte

Les sources TailAdmin utilisent **Tailwind CSS v4** (nouveau moteur, configuration *CSS-first*). Le projet impose **AssetMapper** (pas de Webpack Encore) et vise un pipeline **sans Node.js** si possible, servi par FrankenPHP/PHP 8.5. Tailwind v4 n'a pas de configuration `tailwind.config.js` par défaut : elle se fait dans le CSS via `@import "tailwindcss";` et la directive `@plugin`.

**Enjeu** : c'est le **risque technique n°1** du projet (identifié à l'init). Une mauvaise intégration bloque tout le thème.

## Décision

Utiliser **`symfonycasts/tailwind-bundle`** pour compiler Tailwind v4 et l'intégrer à AssetMapper.

- Le bundle **télécharge le binaire standalone Tailwind** (aucune dépendance Node.js/npm).
- Commandes : `tailwind:init` (setup), `tailwind:build` (compilation), `tailwind:build --watch` (dev).
- Le CSS compilé est **transparent pour AssetMapper** : le bundle substitue la sortie compilée à la source lors du `asset()` / de la compilation.
- **Épingler explicitement `binary_version` sur une version 4.x** dans `config/packages/tailwind.yaml`. ⚠️ Sans version explicite, le bundle **retombe sur `3.4.17`** (avec dépréciation) — comportement à éviter.
- Configuration **CSS-first** : fichier d'entrée `assets/styles/app.css` avec `@import "tailwindcss";`, les plugins via `@plugin "@tailwindcss/forms";`, les tokens TailAdmin en `@theme { … }`.

## Alternatives considérées

### A. Build Node.js / PostCSS classique (Tailwind CLI npm + postcss)
- ✅ Approche « officielle » Tailwind, familière.
- ❌ Impose Node.js dans le dev et la CI (contraire à l'objectif « sans build JS »).
- ❌ Intégration manuelle avec AssetMapper (watch, chemins) — friction.

### B. `symfonycasts/tailwind-bundle` (choisi)
- ✅ **Zéro Node.js** (binaire standalone), support **v4 officiel**.
- ✅ Intégration native AssetMapper, `--watch`, version épinglée (reproductibilité type `composer.lock`).
- ✅ Aligné sur la stack Symfony UX / AssetMapper du projet.
- ❌ Une dépendance de plus ; dépend de la disponibilité des binaires standalone v4.

### C. CSS Tailwind précompilé livré dans le bundle
- ✅ Le consommateur n'a rien à compiler.
- ❌ Tue la personnalisation des tokens (re-branding, exigence P-002) → rejeté comme approche par défaut (envisageable en *option* de distribution ultérieure).

## Conséquences

### Positives
- Pipeline reproductible, sans Node, cohérent avec AssetMapper/FrankenPHP.
- Tokens TailAdmin personnalisables (branding) via `@theme`.
- `dark:` et `ltr:/rtl:` de Tailwind directement exploitables (dark mode US-005, RTL US-024).

### Négatives / points de vigilance
- **POC obligatoire en Sprint 1** (US-002) : prouver la chaîne binaire v4 → CSS → AssetMapper → rendu.
- Distribution du thème comme **bundle** : décider où vit le CSS source (dans le bundle, recompilé par l'app hôte) vs CSS précompilé optionnel → traité en **ADR-003**.
- Vérifier la version exacte du binaire v4 disponible au moment du POC et l'épingler.

## Références

- [SymfonyCasts/tailwind-bundle (GitHub)](https://github.com/SymfonyCasts/tailwind-bundle)
- [TailwindBundle Documentation](https://symfony.com/bundles/TailwindBundle/current/index.html)
- [AssetMapper (Symfony Docs)](https://symfony.com/doc/current/frontend/asset_mapper.html)
