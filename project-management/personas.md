# Personas — tailsfadmin

> Thème admin Symfony inspiré de TailAdmin (bundle réutilisable + application de démo).
> Minimum 3 personas requis (standard SCRUM du projet).

---

## P-001 — Développeur Symfony intégrateur (persona primaire)

| Attribut | Valeur |
|----------|--------|
| **Rôle** | Développeur back/full-stack Symfony |
| **Objectif** | Démarrer rapidement un back-office/admin sans repartir d'une page blanche |
| **Contexte** | Installe le bundle via Composer, utilise AssetMapper, connaît Twig et un peu Stimulus |
| **Niveau technique** | Confirmé Symfony, débutant/intermédiaire front |

**Besoins**
- Installer le thème en quelques minutes (recipe / doc claire).
- Composer des pages admin à partir de composants Twig prêts à l'emploi (`<twig:...>`).
- Ne pas avoir à écrire de JavaScript pour les comportements courants (sidebar, dark mode, dropdowns).
- Étendre/surcharger le layout et les composants proprement.

**Frustrations**
- Templates admin livrés en HTML/React non idiomatiques à Symfony.
- Intégration front (Tailwind, JS libs) fragile et non documentée avec AssetMapper.
- Thèmes non maintenus, non accessibles, sans dark mode cohérent.

**Citation** — « Je veux `composer require`, poser mes composants Twig et avoir un dashboard propre le jour même. »

---

## P-002 — Lead / Designer UI (garant de la fidélité visuelle)

| Attribut | Valeur |
|----------|--------|
| **Rôle** | Tech lead ou designer responsable du design system |
| **Objectif** | Obtenir une UI fidèle à TailAdmin, cohérente, thématisable et accessible |
| **Contexte** | Définit les design tokens (couleurs, typo, espacements), valide l'a11y et le dark mode |
| **Niveau technique** | Fort en design system / CSS, lit le Twig |

**Besoins**
- Fidélité visuelle au design TailAdmin (source de vérité).
- Design tokens centralisés et personnalisables (branding client).
- Dark mode et responsive de première classe.
- Conformité accessibilité (contrastes, focus, ARIA, navigation clavier).

**Frustrations**
- Thèmes « pixel-parfaits » mais impossibles à re-brander.
- Dark mode bricolé, incohérent d'un composant à l'autre.
- Accessibilité traitée après coup.

**Citation** — « La fidélité au design ET la capacité à le re-brander ne doivent pas s'exclure. »

---

## P-003 — Mainteneur / contributeur du bundle (garant de la qualité)

| Attribut | Valeur |
|----------|--------|
| **Rôle** | Mainteneur open-source / interne du bundle tailsfadmin |
| **Objectif** | Un bundle propre, testé, documenté, maintenable dans la durée |
| **Contexte** | Fait évoluer les composants, gère les versions, la CI, la doc |
| **Niveau technique** | Expert Symfony, architecture (SOLID/DDD léger), tests |

**Besoins**
- Architecture claire (séparation bundle / app de démo).
- Composants testables (tests Twig Components, tests fonctionnels).
- CI verte (PHPStan max, tests, lint front), versionnage SemVer.
- Documentation d'usage et de contribution à jour.

**Frustrations**
- Code de thème non testé et non typé.
- Absence de frontière nette entre le réutilisable (bundle) et la démo.
- Dette front (JS non structuré) qui casse à chaque montée de version.

**Citation** — « Si ce n'est ni testé ni documenté, ce n'est pas livrable. »

---

## P-004 — Utilisateur final de l'admin (bénéficiaire indirect)

| Attribut | Valeur |
|----------|--------|
| **Rôle** | Gestionnaire/opérateur utilisant un back-office bâti avec tailsfadmin |
| **Objectif** | Consulter et gérer des données efficacement, sur desktop et mobile |
| **Contexte** | Utilise le dashboard au quotidien, parfois en mobilité |
| **Niveau technique** | Non technique |

**Besoins**
- Interface lisible, rapide, responsive.
- Dark mode confortable, navigation claire (sidebar, breadcrumb).
- Composants interactifs fiables (tableaux, graphiques, calendrier, formulaires).

**Frustrations**
- Interfaces lentes, non responsive, illisibles en faible luminosité.
- Interactions cassées (dropdowns qui ne ferment pas, modals piégeantes).

**Citation** — « Je veux trouver l'info et agir vite, sans me battre avec l'interface. »

---

**Date de création :** 2026-09-07
**Version :** 1.0.0
