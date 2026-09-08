# Test d'intégration — app Symfony vierge (US-030)

Prouve la **consommabilité tierce** du bundle : une app Symfony neuve, créée hors
du monorepo, installe le bundle **via l'archive dist** (honore `.gitattributes`
export-ignore → fidèle à Packagist, contrairement à la démo couplée en path repo)
et rend une page admin fonctionnelle. **La CI EST le test** (job `integration`).

## Pièces

| Fichier | Rôle |
|---------|------|
| `create-app.sh` | Archive dist du bundle, crée le skeleton Symfony (`SYMFONY_REQUIRE`), installe AssetMapper/Stimulus/Tailwind/Panther puis le bundle via path repository sur l'archive extraite. Garde-fou : échoue si `demo/`, `tests/`… fuient dans l'archive. |
| `apply-steps.sh` | Applique les étapes documentées (README) : entrée Tailwind hôte important `theme.css` (US-028), config menu + routes, fixtures de smoke. Ne lance PAS `assets:install`. |
| `fixtures/` | `HomeController` (routes `home`, `chart`, `locale_switch`), templates admin/chart, `SmokeTest` (Panther), `phpunit.xml.dist`, `tailsfadmin.yaml`. |

## Déroulé du job CI (matrice Symfony 7.3 / 8.0)

1. `create-app.sh` → app tierce + bundle installé.
2. `apply-steps.sh` → étapes d'intégration.
3. **Phase échec** (avant vendoring) : build assets sans `assets:install`, `phpunit --group failure` → le préflight US-027 doit marquer `<html data-tailsfadmin-missing-libs>` (ApexCharts manquant).
4. **Phase nominal** : `tailsfadmin:assets:install` + rebuild, `phpunit --group nominal` → layout admin (sidebar/header), thème appliqué (police Outfit), Dropdown qui monte au clic.

## Lancer en local

Nécessite Chrome + ChromeDriver et les variables `PANTHER_CHROME_BINARY` /
`PANTHER_CHROME_DRIVER_BINARY` (cf. job `e2e`) :

```bash
bash tests/integration/create-app.sh /tmp/host-app 8.0
bash tests/integration/apply-steps.sh /tmp/host-app
cd /tmp/host-app
php bin/console tailwind:build && php bin/console asset-map:compile
php bin/phpunit --group failure
php bin/console tailsfadmin:assets:install
php bin/console tailwind:build && php bin/console asset-map:compile
php bin/phpunit --group nominal
```
