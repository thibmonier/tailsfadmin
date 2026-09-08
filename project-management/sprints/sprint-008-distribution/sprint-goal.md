# Sprint 008 — Distribution & consommabilité (v2)

## Informations

| Attribut | Valeur |
|----------|--------|
| Numéro | 008 (v2 — EPIC-008) |
| Durée | 2 semaines (10 j) |
| Capacité | ~19-24 pts (1 dev) |
| Engagé | **21 points** (US-029 en réserve/stretch) |
| Prérequis | v1.0.0 livré + CI verte + tag/release |

## Sprint Goal

> **Rendre tailsfadmin installable et fonctionnel dans une application Symfony
> vierge (cible : hottwos) sans recopie manuelle d'assets, et le publier sur
> Packagist — un `composer require` suffit pour obtenir un back-office stylé et
> interactif.**

C'est le sprint **enabler** de la v2 : sans consommabilité, les pages
supplémentaires (EPIC-009) n'ont pas d'intérêt réutilisable.

## Sprint Backlog

| Priorité | ID | Titre | Points | Statut |
|----------|-----|-------|--------|--------|
| 🔴 Must | US-027 | Recette d'assets : importmap fourni par le bundle | 8 | 🔵 To Do |
| 🔴 Must | US-028 | Thème CSS Tailwind distribuable + personnalisable | 5 | 🔵 To Do |
| 🔴 Must | US-030 | Test d'intégration app Symfony vierge (CI) | 5 | 🔵 To Do |
| 🔴 Must | US-031 | Publication Packagist | 3 | 🔵 To Do |
| 🟡 Should | US-029 | Flex recipe (config auto) | 5 | 🟣 Réserve |

**Total engagé : 21 points** (+ US-029 en stretch si capacité).

## Ordre de développement recommandé

`US-027 (assets/importmap) + US-028 (CSS) → US-030 (test app vierge, valide 027/028) → US-031 (Packagist, après intégration prouvée) → US-029 (recette, si stretch)`

## Décisions actées

- **Packagist d'abord** : canal de distribution retenu ; publication **après** une intégration prouvée (US-030 verte).
- **Sans Node / sans CDN** conservé (ADR-001/004/006).
- Intégration **hottwos** possible dès maintenant via **VCS repo + v1.0.0** (recopie temporaire d'importmap) ; la v2 supprime ce copier-coller.

## Risques

| Risque | Prob. | Impact | Mitigation |
|--------|-------|--------|------------|
| L'API AssetMapper ne permet pas de `prepend` l'importmap proprement | Moyenne | Haut | Fallback : commande d'install dédiée + doc ; vendoring scripté |
| CSS du bundle non scanné côté hôte (utilitaires manquants) | Moyenne | Moyen | `@source` distribué + smoke CSS dans le test d'intégration (US-030) |
| Couverture symlink/path-repo en CI | Faible | Faible | Test d'intégration sur app réelle plutôt que couverture |
| Publication Packagist prématurée (mauvaise 1re impression) | Faible | Moyen | Publier après US-030 verte uniquement |

## Cérémonies

| Cérémonie | Objet |
|-----------|-------|
| Planning P1 | Sprint Goal + périmètre (ce document) |
| Planning P2 | Décomposition (`/project:decompose-tasks 008`) |
| Daily | Avancement, blocages (importmap prepend, CSS scan) |
| Review | Démo : `composer require` sur app vierge → page admin stylée + interactive |
| Rétro | Directive Fondamentale |

## Directive Fondamentale de la Rétrospective

> « Indépendamment de ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait le meilleur travail possible, compte tenu de ce qui était connu à ce moment-là, de ses compétences et capacités, des ressources disponibles et de la situation du moment. » — Norman Kerth

---

**Prochaine étape :** `/project:decompose-tasks 008` puis développement TDD (US-027 → US-031), avec test d'intégration sur app vierge et revue visuelle.
