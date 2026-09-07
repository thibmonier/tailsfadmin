# Sprint 001 — Walking Skeleton

## Sprint Goal

> **Prouver la chaîne technique de bout en bout : une application de démo qui démarre sous FrankenPHP/PHP 8.5, consommant un bundle Symfony réutilisable, avec un layout admin rendu par Tailwind v4 (via AssetMapper) et un premier contrôleur Stimulus (preloader) packagé depuis le bundle.**

C'est le *Walking Skeleton* : fonctionnalité complète minimale traversant toutes les couches (bundle → démo → assets → runtime → UI interactive).

> **Ajustement de périmètre (2026-09-07)** : **US-005 (dark mode) est déplacée en Sprint 2** pour ramener la charge sous la capacité. Les **tokens dark** restent définis en US-002 ; seule la **bascule interactive** est reportée. La chaîne JS reste prouvée par le contrôleur `preloader` (US-004) + le packaging `T-TECH-04`.

---

## Périmètre du sprint

| ID | Titre | Points | Priorité |
|----|-------|--------|----------|
| US-001 | Squelette bundle + application de démo | 5 | Must |
| US-002 | AssetMapper + Tailwind v4 + design tokens | 8 | Must |
| US-003 | Exécution FrankenPHP / PHP 8.5 | 3 | Must |
| US-004 | Layout admin de base | 5 | Must |

**Total engagé :** 21 points (US-005 déplacée en Sprint 2)

---

## Definition of Success du sprint

- [ ] `git clone` → l'app de démo démarre sous FrankenPHP (PHP 8.5).
- [ ] Le bundle est installé/consommé par la démo (séparation nette prouvée).
- [ ] Tailwind v4 compile et sert le CSS via AssetMapper (**ADR validé**).
- [ ] Une page rend le layout admin (header + sidebar + content + preloader).
- [ ] Un **contrôleur Stimulus du bundle (preloader)** s'auto-enregistre dans la démo (chaîne JS prouvée, ADR-003).
- [ ] Les **design tokens dark** sont définis (bascule interactive = Sprint 2, US-005).
- [ ] Aucun Alpine.js ; aucune erreur console.

---

## Risque principal levé par ce sprint

**Intégration Tailwind v4 ⇄ AssetMapper** — c'est l'inconnue technique majeure du projet. La lever dès le Sprint 1 sécurise tout le reste du backlog. → à cadrer par un **ADR** en phase de Conception (`/workflow:design`) avant l'implémentation.

---

## Cérémonies

| Cérémonie | Objet |
|-----------|-------|
| **Planning (Part 1)** | Sélection du périmètre (ci-dessus), confirmation du Sprint Goal |
| **Planning (Part 2)** | Décomposition en tâches (`/project:decompose-tasks 001`) |
| **Daily** | Point d'avancement, blocages (surtout ADR Tailwind v4) |
| **Review** | Démo : app qui démarre + layout + dark mode |
| **Rétrospective** | Directive Fondamentale ci-dessous |
| **Affinage** | Préparer le Sprint 2 (layout avancé, premiers composants UI) |

---

## Directive Fondamentale de la Rétrospective

> « Indépendamment de ce que nous découvrons, nous comprenons et croyons sincèrement que chacun a fait le meilleur travail possible, compte tenu de ce qui était connu à ce moment-là, de ses compétences et capacités, des ressources disponibles et de la situation du moment. »
> — Norman Kerth

---

**Prochaine étape :** phase de Conception (`/workflow:design`) → Tech Spec + ADRs, puis `/project:decompose-tasks 001`.
