<?php

declare(strict_types=1);

namespace Tailsfadmin\Assets;

/**
 * Résultat d'un {@see ImportMapInstallPlanner::plan()} : ce qui doit être requis,
 * ce qui est déjà satisfait (idempotence) et les conflits de version.
 */
final readonly class InstallPlan
{
    /**
     * @param list<RequiredPackage> $toRequire paquets à ajouter à l'importmap
     * @param list<string>          $skipped   noms d'import déjà satisfaits
     * @param list<VersionConflict> $conflicts entrées présentes dans une autre version
     */
    public function __construct(
        public array $toRequire,
        public array $skipped,
        public array $conflicts,
    ) {
    }

    public function hasConflicts(): bool
    {
        return [] !== $this->conflicts;
    }
}
