# Sprint Review — Sprint 003 (UI Kit)

## Informations

| Attribut | Valeur |
|----------|--------|
| Date | 2026-09-07 |
| Sprint | 003 — Bibliothèque de composants UI |
| Contexte | Projet solo — review = inspection de l'incrément |

## Sprint Goal

> **Livrer le cœur de la bibliothèque de composants UI — alertes, badges, avatars, boutons, modales et dropdowns — en Twig Components accessibles et fidèles à TailAdmin.**

**Atteint : ✅ OUI** — 6 familles de composants `tsf:Ui:*` livrées, accessibles, dark mode, présentées dans une galerie `/ui-kit`.

## User Stories livrées

| ID | Titre | Points | Démo | Statut |
|----|-------|--------|------|--------|
| US-008 | Alerts (4 variantes + dismissible) | 3 | ✅ | ✅ Livré |
| US-009 | Badges (6) & Avatars (tailles + statut) | 3 | ✅ | ✅ Livré |
| US-010 | Buttons (6 variantes, icônes, loading, disabled, href) | 3 | ✅ | ✅ Livré |
| US-012 | Dropdown (réutilise le contrôleur US-007) | 5 | ✅ | ✅ Livré |
| US-011 | Modals accessibles (focus trap, Échap, overlay) | 5 | ✅ | ✅ Livré |

**Livré : 19/19 points (100 %)**

## Métriques

| Métrique | Valeur |
|----------|--------|
| Points engagés / livrés | 19 / 19 |
| Vélocité | 19 (S1 21, S2 21, S3 19) |
| Tâches | 25/25 |
| Tests | **78 fonctionnels + 2 E2E Panther** |
| PHPStan (max) | 0 erreur |
| php-cs-fixer | 0 |
| Régression | 0 |

## Démonstration (reproductible)

```bash
cd demo && composer test         # 78/78 (dont smoke-test CSS)
composer test:e2e                # 2/2 Panther (navigateur réel) — nécessite Chromium
php -S 127.0.0.1:8055 -t public  # http://127.0.0.1:8055/ui-kit
```
- **Galerie `/ui-kit`** : alertes (4 + dismissible ×), badges (7 couleurs + tailles), avatars (6 tailles + pastille statut), boutons (variantes/tailles/loading/disabled/lien), dropdowns, modales.
- **Modale** vérifiée au runtime : ouverture au clic (dialog + overlay), fermeture Échap.

## Incrément produit

- **Composants** `tsf:Ui:` : `Alert`, `Badge`, `Avatar`, `Button`, `Dropdown`, `Modal`.
- **Contrôleurs Stimulus** : `alert-dismiss` (nouveau), `modal` (nouveau, focus trap) ; `dropdown` réutilisé (US-007, DRY).
- **Page galerie** `/ui-kit` (`UiKitController`).
- **Garde-fous qualité** (issus de l'incident CSS Sprint 2) : `CssBuildTest` (smoke-test du CSS compilé via le manifest) + `symfony/panther` (2 tests navigateur, isolés de `composer test`).
- **Compat** : contrainte `ux-twig-component` élargie à `^2.0 || ^3.0` (meilleure compatibilité consommateur).

## Feedback / décisions

### Positif
- **Réutilisation** : US-012 (dropdown) a coûté peu grâce au contrôleur générique du Sprint 2 — le pari « composant générique » paie.
- **Les 2 garde-fous répondent directement à l'incident CSS** : une régression de style ou un comportement JS cassé serait désormais attrapé (CssBuildTest / Panther).
- Galerie `/ui-kit` = excellent support de revue visuelle et de future documentation.

### À améliorer / points de suivi
- **Incident login** en cours de sprint (sous-agent) : géré sans perte (travail déjà vert au moment de l'expiration, revérifié). Rappel : toujours revérifier l'état réel après une interruption.
- Étendre progressivement la couverture Panther (spike → quelques parcours clés), sans alourdir `composer test`.

### Impact sur le backlog
- Aucun ajout. **Sprint 4** proposé : finir EPIC-003 (US-013 cards/media/videos) + démarrer EPIC-004 (formulaires & tables : US-014).

## Prochaines étapes
1. Rétrospective.
2. Commit du Sprint 3.
3. Sprint 4 (US-013 + EPIC-004).
