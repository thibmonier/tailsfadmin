# Spec smoke CSS — US-028 / T-028-05

> **Statut** : spécification. **Exécution** : US-030 (test d'intégration app
> Symfony vierge). Ce document fige les assertions que le smoke test d'intégration
> devra vérifier sur le CSS compilé de l'app hôte après intégration du thème.

## Prérequis d'exécution (US-030)

Dans l'app vierge, après :

1. `composer require tailsfadmin/tailsfadmin-bundle` (ou path/VCS),
2. entrée Tailwind hôte = `@import "tailwindcss"; @import ".../theme.css";`,
3. `php bin/console tailwind:build`,

le fichier compilé (`var/tailwind/app.built.css` ou la sortie configurée) doit
satisfaire les assertions ci-dessous.

## Assertions

### A. Tokens de marque exposés (T-028-01)
- [ ] Le CSS définit `--color-brand-500` (valeur par défaut `#465fff`).
- [ ] Au moins une utilité de marque (`bg-brand-500` / `text-brand-*`) référence
      `var(--color-brand-500)`.

### B. Classes composants générées via `@source` (T-028-02)
- [ ] `.menu-item` **et** `.menu-item-active` sont présents dans la sortie
      (preuve que `@source` scanne bien les templates du bundle en `vendor/`).
- [ ] Au moins une classe `.jvm-*` (jsvectormap) ou un composant équivalent
      uniquement défini côté bundle est généré.

### C. Rebranding surchargeable (T-028-03)
- [ ] Après ajout hôte de `:root { --color-brand-500: #7c3aed }` **sous** l'import,
      la valeur effective de `--color-brand-500` dans la cascade est `#7c3aed`
      (la redéfinition `:root` l'emporte sur le `@theme` du thème).

### D. Dark mode — pas d'inversion des gris (garde v1, action rétro)
- [ ] Le bloc `.dark` est présent et éclaircit la marque
      (`--color-brand-500: #7592ff`) + `--color-gray-dark`.
- [ ] **Aucune** inversion de l'échelle de gris : `--color-gray-900` reste sombre
      (`#101828`) sous `.dark` (régression Sprint 2 à ne pas réintroduire).

### E. Anti-FOUC / forms
- [ ] La police Outfit est importée en `layer(base)`.
- [ ] Le plugin `@tailwindcss/forms` est actif (styles de reset de formulaire présents).

## Note d'implémentation

Ces assertions sont des **greps sur le CSS compilé** (pas de navigateur requis) ;
le montage JS réel et la revue visuelle clair/dark relèvent respectivement d'US-030
(smoke Chrome) et de la revue T-028-06. Preuve de faisabilité : le build local de
la démo (`demo/assets/styles/app.css` → thème) génère déjà A, B, D et E.
