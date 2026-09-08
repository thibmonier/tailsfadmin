<?php

declare(strict_types=1);

namespace Tailsfadmin\Assets;

/**
 * Un paquet que la commande `tailsfadmin:assets:install` doit ajouter à
 * l'importmap de l'hôte (cf. ADR-007). Value object immuable produit par
 * {@see ImportMapInstallPlanner}.
 *
 * - Entrée distante : {@see $version} défini, {@see $localPath} null.
 * - Entrée locale (shim livré par le bundle) : {@see $localPath} défini,
 *   {@see $version} null.
 */
final readonly class RequiredPackage
{
    public function __construct(
        public string $importName,
        public string $packageModuleSpecifier,
        public ?string $version = null,
        public ?string $localPath = null,
    ) {
    }

    public function isLocal(): bool
    {
        return null !== $this->localPath;
    }
}
