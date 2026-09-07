# Sprint Review — Sprint 002 (Navigation & layout interactifs)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-07 |
| Sprint | 002 — Navigation & layout interactifs |
| Contexte | Projet solo — review = inspection de l'incrément |

## Sprint Goal

> **Transformer le layout statique du Walking Skeleton en une navigation pleinement interactive : dark mode persistant, sidebar responsive multi-niveaux et header fonctionnel — le tout en Stimulus/UX, sans Alpine.js.**

**Atteint : ✅ OUI** — le chrome de l'admin est désormais interactif de bout en bout, 100 % Stimulus.

## User Stories livrées

| ID | Titre | Points | Démo | Statut |
|----|-------|--------|------|--------|
| US-005 | Dark mode persistant (Stimulus) | 5 | ✅ | ✅ Livré |
| US-006 | Sidebar responsive multi-niveaux + MenuBuilder | 8 | ✅ | ✅ Livré |
| US-007 | Header (recherche Cmd+K, dropdowns accessibles) | 8 | ✅ | ✅ Livré |

**Livré : 21/21 points (100 %)**

## Métriques

| Métrique | Valeur |
|----------|--------|
| Points engagés / livrés | 21 / 21 |
| Vélocité | 21 (identique au Sprint 1) |
| Tâches | 21/21 |
| Tests | **46 verts** (bundle 14 + démo 32, 148 assertions) |
| PHPStan (max) | 0 erreur |
| php-cs-fixer | 0 |
| Régression | 0 (dark mode, sidebar, preloader coexistent) |

## Démonstration (reproductible)

```bash
cd demo && composer test        # 32/32 (build Tailwind + compile + phpunit)
php -S 127.0.0.1:8000 -t public # puis ouvrir http://127.0.0.1:8000/
# ou: docker compose up --build -d  (FrankenPHP)
```
- **Dark mode** : bouton soleil/lune dans le header → bascule `.dark`, persistée (localStorage), sans FOUC au reload.
- **Sidebar** : repliable en desktop (mode icônes, persisté) ; drawer + overlay + focus trap en mobile ; item actif surligné ; menu piloté par `MenuBuilder` (configurable via `demo/config/packages/tailsfadmin.yaml`).
- **Header** : `Cmd/Ctrl+K` et `/` ouvrent la recherche (ignorés si un champ a le focus) ; dropdowns user + notifications accessibles (clavier, `aria-expanded`, `role=menu`, Échap, clic extérieur).

## Incrément produit

- **Contrôleurs Stimulus** (bundle) : `theme`, `sidebar`, `dropdown` (générique), `search` — tous distribués via `package.json`/`controllers.json`.
- **Service `MenuBuilder`** + value objects `MenuItem`/`MenuGroup` (menu configurable, calqué sur `MenuHelper`).
- **Twig Extension** : `is_active()`, `tsf_icon()` (9 SVG).
- **Composants** `tsf:Layout:{Header,Sidebar}` refactorés dynamiques, RTL anticipé (`ltr:`/`rtl:`).

## Feedback / décisions

### Positif
- Le contrôleur **`dropdown` est générique** → réutilisable immédiatement au Sprint 3 (menus d'actions de tables, etc.).
- **`MenuBuilder` configurable** → le consommateur définit son menu sans toucher au bundle (réutilisabilité prouvée).
- **RTL anticipé** dès la sidebar : évite un refactor coûteux quand l'i18n (US-024) arrivera.
- Séquencement séquentiel sur fichiers partagés (layout/header/controllers.json) → **zéro conflit**.

### À améliorer / limites
- Les **comportements JS purs** (bascule dark réelle, navigation clavier des dropdowns, raccourcis) ne sont couverts qu'au niveau **DOM/ARIA** par les tests fonctionnels — pas de test navigateur réel. → envisager Panther/Playwright (piste pour US-025 a11y ou un sprint dédié).

### Impact sur le backlog
- Aucun ajout. **Sprint 3** proposé : EPIC-003 (composants UI : alerts, badges/avatars, buttons, modals, dropdowns — déjà là, cards).

## Prochaines étapes
1. Rétrospective (`sprint-retro.md`).
2. Commit du Sprint 2 (Conventional Commits).
3. Rendu visuel de la démo (screenshots).
4. Sprint 3 (EPIC-003).
