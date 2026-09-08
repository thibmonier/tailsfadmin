<?php

declare(strict_types=1);

namespace Tailsfadmin\Assets;

/**
 * Décide, à partir du manifeste des dépendances JS du bundle
 * (`config/importmap-entries.php`) et des entrées déjà présentes dans
 * l'importmap de l'hôte, quels paquets requérir, lesquels ignorer (idempotence)
 * et lesquels sont en conflit de version.
 *
 * Logique PURE, sans dépendance AssetMapper (cf. ADR-007) : la commande
 * `tailsfadmin:assets:install` fournit l'état présent et exécute le plan.
 */
final class ImportMapInstallPlanner
{
    /**
     * @param array<string, array<string, mixed>> $manifest       entrées du manifeste (nom d'import => spec)
     * @param array<string, string|null>          $presentEntries nom d'import => version (null = entrée locale/sans version)
     */
    public function plan(array $manifest, array $presentEntries, bool $force = false): InstallPlan
    {
        $toRequire = [];
        $skipped = [];
        $conflicts = [];

        foreach ($manifest as $importName => $spec) {
            $package = $this->toPackage((string) $importName, $spec);
            $isPresent = \array_key_exists($importName, $presentEntries);

            if (!$isPresent) {
                $toRequire[] = $package;
                continue;
            }

            if ($force) {
                $toRequire[] = $package;
                continue;
            }

            // Entrée locale déjà présente : considérée satisfaite (pas de version à comparer).
            if ($package->isLocal()) {
                $skipped[] = $package->importName;
                continue;
            }

            $presentVersion = $presentEntries[$importName];
            if ($presentVersion === $package->version) {
                $skipped[] = $package->importName;
                continue;
            }

            $conflicts[] = new VersionConflict($package->importName, (string) $package->version, $presentVersion);
        }

        return new InstallPlan($toRequire, $skipped, $conflicts);
    }

    /**
     * @param array<string, mixed> $spec
     */
    private function toPackage(string $importName, array $spec): RequiredPackage
    {
        if (isset($spec['local'])) {
            return new RequiredPackage(
                importName: $importName,
                packageModuleSpecifier: $importName,
                localPath: $this->stringField($spec, 'local', $importName),
            );
        }

        return new RequiredPackage(
            importName: $importName,
            packageModuleSpecifier: isset($spec['package_specifier'])
                ? $this->stringField($spec, 'package_specifier', $importName)
                : $importName,
            version: isset($spec['version'])
                ? $this->stringField($spec, 'version', $importName)
                : null,
        );
    }

    /**
     * Lit une clé du manifeste en garantissant qu'elle est une chaîne.
     *
     * @param array<string, mixed> $spec
     */
    private function stringField(array $spec, string $key, string $importName): string
    {
        $value = $spec[$key];
        if (!\is_string($value)) {
            throw new \InvalidArgumentException(\sprintf(
                'Manifeste importmap invalide : la clé « %s » de « %s » doit être une chaîne.',
                $key,
                $importName,
            ));
        }

        return $value;
    }
}
