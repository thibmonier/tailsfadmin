# Tâches — US-030 : Test d'intégration dans une app Symfony vierge (CI)

## Informations US
- **Epic** : EPIC-008-distribution-consommabilite · **Persona** : P-003 · **Points** : 5 · **Sprint** : sprint-008

## Résumé
**En tant que** mainteneur **je veux** un job CI qui crée une app Symfony neuve, y installe le bundle (comme un tiers) et vérifie qu'une page admin s'affiche et fonctionne **afin de** garantir que la consommabilité (US-027/028/029) marche hors du monorepo de la démo.

> La démo consomme le bundle en **path repository** (couplée) : elle ne prouve pas l'expérience tierce. Ce test crée un projet Symfony minimal (skeleton + AssetMapper), installe le bundle via le canal cible (VCS/path avant publication ; Packagist après US-031), applique les étapes documentées, et lance un smoke test. **La CI EST le test** (vert/rouge).

## Vue d'ensemble des tâches

| ID | Type | Tâche | Est. | Dépend de | Statut |
|----|------|-------|------|-----------|--------|
| T-030-01 | [OPS] | Script création app Symfony neuve + install bundle (VCS/path) | 3h | US-027, US-028 | 🔲 |
| T-030-02 | [OPS] | Appliquer les étapes documentées (assets 027, CSS 028, recette 029 si prête) | 2h | T-030-01 | 🔲 |
| T-030-03 | [TEST] | Smoke : layout admin → 200 + sidebar/header + CSS + 1 composant JS | 3h | T-030-02 | 🔲 |
| T-030-04 | [TEST] | Scénario d'échec (vendoring manquant → rouge + message) | 1.5h | T-030-03 | 🔲 |
| T-030-05 | [OPS] | Job CI `integration` bloquant sur PR (matrice Symfony si pertinent) | 1.5h | T-030-03, T-030-04 | 🔲 |
| T-030-06 | [REV] | Review | 0.5h | T-030-05 | 🔲 |

**Total : 11.5h**

---

## Détail

### T-030-01 · [OPS] Script création app vierge + install — 3h
**Fichiers** : `.github/workflows/ci.yml` (job `integration`), `tests/integration/create-app.sh` (nouveau) ou étapes inline du job.
**Critères** :
- [ ] `composer create-project symfony/skeleton` (versions cibles) + AssetMapper + Tailwind standalone.
- [ ] Installe le bundle via **VCS/path repository** (préparation ; bascule Packagist après US-031).
- [ ] App hors monorepo (répertoire distinct de `demo/`).

### T-030-02 · [OPS] Appliquer les étapes documentées — 2h
**Critères** :
- [ ] Étape assets US-027 (commande d'install / vendoring) appliquée.
- [ ] Étape CSS US-028 (import du thème + `@source`) appliquée.
- [ ] Recette US-029 appliquée **si prête** (sinon config manuelle documentée).
- [ ] Une route + page de test étendant le layout admin ajoutée à l'app.

### T-030-03 · [TEST] Smoke test app tierce — 3h
**Fichiers** : test Panther dans l'app générée (ex. `tests/Smoke/AdminPageSmokeTest.php`).
**Critères** (scénario nominal Gherkin) :
- [ ] Une page étend `@Tailsfadmin/layout/admin.html.twig` → HTTP **200** avec **sidebar + header** rendus.
- [ ] CSS du thème appliqué (**classes générées** — reprend la spec T-028-05, dark sans inversion des gris).
- [ ] Un **composant JS monte réellement** (ex. `tsf:Ui:Dropdown` ou `tsf:Ui:Modal` au navigateur).

### T-030-04 · [TEST] Scénario d'échec — 1.5h
**Critères** (scénario 2 Gherkin) :
- [ ] Sans l'étape de vendoring (US-027), charger un `tsf:Chart:*` fait **échouer** le test avec un message pointant l'étape manquante.

### T-030-05 · [OPS] Job CI `integration` — 1.5h
**Fichiers** : `.github/workflows/ci.yml`.
**Critères** :
- [ ] Job dédié (Chrome/ChromeDriver comme le job `e2e` existant : `PANTHER_NO_SANDBOX`, `--headless=new`).
- [ ] **Bloquant sur PR**.
- [ ] Matrice de versions Symfony (`^7.3`, `^8.0`) si pertinent au regard du `require` composer.

### T-030-06 · [REV] Review — 0.5h
Relecture script + job ; DoD.

## Graphe
```mermaid
graph TD
    A[T-030-01 create app + install] --> B[T-030-02 étapes documentées]
    B --> C[T-030-03 smoke nominal]
    C --> D[T-030-04 scénario échec]
    C --> E[T-030-05 job CI integration]
    D --> E
    E --> R[T-030-06 review]
```

## Dépendances
- **Dépend de** : US-027, US-028 (US-029 si recette prête).
- **Bloque** : US-031 (publier après intégration prouvée).
