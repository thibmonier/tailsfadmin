<?php

declare(strict_types=1);

namespace Tailsfadmin\Assets;

/**
 * Signale qu'une entrée du manifeste du bundle est déjà pinée par l'hôte dans
 * une version différente. La commande n'écrase pas sans `--force` (ADR-007).
 */
final readonly class VersionConflict
{
    public function __construct(
        public string $importName,
        public string $wantedVersion,
        public ?string $presentVersion,
    ) {
    }
}
