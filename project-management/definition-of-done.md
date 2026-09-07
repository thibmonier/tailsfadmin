# Definition of Done — tailsfadmin

> Une User Story n'est **Done** que si TOUS les critères ci-dessous sont satisfaits.
> Adapté à un projet de thème/UI (bundle Symfony + app de démo).

---

## 1. Fonctionnel

- [ ] Tous les critères d'acceptation (Gherkin) de la story sont vérifiés.
- [ ] Le rendu est **fidèle à la source TailAdmin** (comparaison visuelle avec `Tools/sources/`).
- [ ] Fonctionne en **mode clair ET dark mode**.
- [ ] **Responsive** validé (mobile, tablette, desktop).

## 2. Qualité du code (Back / Symfony)

- [ ] PHP 8.5, typé strictement, **PHPStan niveau max** sans erreur.
- [ ] PSR-12 respecté (php-cs-fixer / lint OK).
- [ ] Principes **SOLID / KISS / DRY / YAGNI** respectés.
- [ ] Composants Twig idiomatiques (Twig Components / Live Components), pas de logique métier dans les templates.

## 3. Qualité du front

- [ ] Interactivité en **Stimulus / Symfony UX** (pas d'Alpine.js résiduel ; conversion effective).
- [ ] Assets servis via **AssetMapper / importmap** (pas de bundler externe non prévu).
- [ ] Tailwind v4 : classes cohérentes, tokens centralisés, pas de CSS mort.
- [ ] Aucun warning console au chargement de la page de démo.

## 4. Accessibilité

- [ ] Navigation **clavier** complète (focus visible, ordre logique).
- [ ] Attributs **ARIA** appropriés sur composants interactifs (menus, modals, tabs).
- [ ] **Contrastes** conformes WCAG AA (clair et dark).
- [ ] Modals : focus trap + fermeture Échap.

## 5. Tests

- [ ] Tests des composants Twig (rendu, props, variantes) via Pest/PHPUnit.
- [ ] Tests fonctionnels sur les pages/routes de démo concernées.
- [ ] Couverture ≥ 80 % sur le code PHP nouvellement introduit.
- [ ] Tous les tests passent en local et en CI.

## 6. Séparation bundle / démo

- [ ] Le code réutilisable est dans le **bundle** ; la démo ne contient que de l'usage.
- [ ] Le composant/la page est **consommable depuis un projet tiers** (pas de dépendance à la démo).
- [ ] Surcharge/personnalisation documentée (override de template, tokens).

## 7. Documentation

- [ ] Usage du composant documenté (props, slots, exemple `<twig:...>`).
- [ ] CHANGELOG mis à jour (Keep a Changelog).
- [ ] README/doc d'installation à jour si la story impacte le setup.

## 8. Intégration & livraison

- [ ] Branche à jour avec `main`, commits **Conventional Commits**.
- [ ] PR relue (self-review + revue), CI verte.
- [ ] Aucune régression sur les pages existantes de la démo.
- [ ] L'application de démo **démarre** (FrankenPHP / PHP 8.5) et la page est accessible.

---

**Date de création :** 2026-09-07
**Version :** 1.0.0
