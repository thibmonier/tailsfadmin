# ADR-006 — Intégration d'une bibliothèque JS tierce via importmap

## Statut

Accepté (2026-09-08)

## Contexte

tailsfadmin encapsule plusieurs bibliothèques JS de TailAdmin (ApexCharts,
FullCalendar, flatpickr, Dropzone, jsvectormap) sous forme de contrôleurs
Stimulus. Le projet est **sans Node au runtime** (AssetMapper + importmap,
Tailwind via binaire standalone) et proscrit tout **CDN au runtime**
(reproductibilité offline). Il fallait une recette reproductible pour ajouter
une lib, capitalisée au fil des Sprints 5 et 6.

## Décision

Toute lib JS tierce suit la même chaîne d'intégration :

1. **Vendoring via importmap** (pas de CDN) :
   ```bash
   bin/console importmap:require <lib>
   bin/console importmap:require <lib>/dist/<lib>.min.css   # si CSS
   ```
   Les fichiers atterrissent dans `demo/assets/vendor/<lib>/` et sont
   **versionnés** (`installed.php` avec digests) pour un build offline.

2. **CSS de la lib chargé par le pipeline** — jamais un `<link>` : on l'importe
   **dans le contrôleur** (résolu par l'entrée importmap `type: 'css'`) :
   ```js
   import "<lib>/dist/<lib>.min.css";
   ```
   (Leçon Sprint 5 : sans cela, le composant s'affiche sans style/positionnement.)

3. **Wrapper Stimulus** (ADR-004) : `connect()` instancie la lib sur
   `this.element`, stocke l'instance ; `disconnect()` la détruit (zéro fuite).
   Données passées en `data-*-value` (JSON), thème sombre géré par
   `MutationObserver` sur `.dark` et/ou par les surcharges CSS `.<lib>-*`.

4. **Déclaration DOUBLE du contrôleur** (indispensable) :
   - `assets/package.json` → section `symfony.controllers` (nom + `main`),
   - `demo/assets/controllers.json` → `enabled: true`.
   > **Piège** : sans l'entrée dans `assets/package.json`, StimulusBundle lève
   > *« Controller "…" does not exist in the "…" package »* (rencontré en US-019).

5. **Composant Twig** (optionnel) : `tsf:Chart:*` / `tsf:Form:*` sérialise les
   props PHP en JSON vers les `data-*-value`.

## Alternatives considérées

- **CDN au runtime** : rejeté (offline, supply-chain, reproductibilité).
- **Bundler Node (Webpack/Vite)** : rejeté (contrainte sans-Node du projet).
- **`<link>` CSS statique** : rejeté (le CSS doit suivre le graphe AssetMapper).

## Conséquences

### Positives
- Recette uniforme, offline, reproductible ; 5 libs intégrées sans friction.
- Pas de dépendance Node au runtime ni de CDN.

### Négatives / vigilance
- Pièges de packaging à connaître : stubs de résolution (ex. `@fullcalendar/core`
  v7 → importer `/index.js`), manifeste `package.json` obligatoire.
- Les assets vendorés gonflent le dépôt (accepté pour la reproductibilité).

## Références
- ADR-003 (packaging assets & contrôleurs Stimulus), ADR-004 (Alpine → Stimulus).
- Sprints 5 (data-viz) et 6 (US-019 carte jsvectormap).
