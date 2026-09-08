<?php

declare(strict_types=1);

namespace Tailsfadmin\Command;

use Symfony\Component\AssetMapper\ImportMap\ImportMapConfigReader;
use Symfony\Component\AssetMapper\ImportMap\ImportMapManager;
use Symfony\Component\AssetMapper\ImportMap\PackageRequireOptions;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tailsfadmin\Assets\ImportMapInstallPlanner;
use Tailsfadmin\Assets\RequiredPackage;

/**
 * Ajoute à l'importmap de l'application hôte les dépendances JS du bundle
 * (ApexCharts, jsvectormap, flatpickr, Dropzone, FullCalendar) et les vendore
 * localement — sans CDN au runtime. Voir ADR-007.
 *
 * Le manifeste des pins (config/importmap-entries.php) est la source de vérité ;
 * {@see ImportMapInstallPlanner} décide quoi requérir (idempotent, détecte les
 * conflits de version), et cette commande exécute le plan via l'API AssetMapper.
 */
#[AsCommand(
    name: 'tailsfadmin:assets:install',
    description: 'Installe les dépendances JS du bundle dans l\'importmap de l\'app (vendoring local, sans CDN)',
)]
final class AssetsInstallCommand extends Command
{
    public function __construct(
        private readonly ImportMapConfigReader $configReader,
        private readonly ImportMapManager $importMapManager,
        private readonly ImportMapInstallPlanner $planner,
        private readonly string $manifestPath,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'force',
            null,
            InputOption::VALUE_NONE,
            'Réécrit les entrées déjà pinées par l\'hôte dans une autre version',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $manifest = $this->loadManifest();
        $force = true === $input->getOption('force');

        $plan = $this->planner->plan($manifest, $this->presentEntries(), $force);

        foreach ($plan->conflicts as $conflict) {
            $io->warning(\sprintf(
                '« %s » est déjà pinée en %s ; le bundle attend %s. Relancez avec --force pour réécrire.',
                $conflict->importName,
                $conflict->presentVersion ?? '(version inconnue)',
                $conflict->wantedVersion,
            ));
        }

        if ([] === $plan->toRequire) {
            $io->success(\sprintf(
                'Assets tailsfadmin déjà installés (%d entrée(s) à jour).',
                \count($plan->skipped),
            ));

            return $plan->hasConflicts() ? Command::FAILURE : Command::SUCCESS;
        }

        $this->importMapManager->require(array_map(
            fn (RequiredPackage $package): PackageRequireOptions => $this->toRequireOptions($package),
            $plan->toRequire,
        ));

        $io->success(\sprintf(
            '%d entrée(s) ajoutée(s) à l\'importmap et vendorée(s) : %s',
            \count($plan->toRequire),
            implode(', ', array_map(static fn (RequiredPackage $p): string => $p->importName, $plan->toRequire)),
        ));

        if ([] !== $plan->skipped) {
            $io->writeln(\sprintf('<info>%d entrée(s) déjà présente(s) ignorée(s).</info>', \count($plan->skipped)));
        }

        return $plan->hasConflicts() ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function loadManifest(): array
    {
        /** @var array<string, array<string, mixed>> $manifest */
        $manifest = require $this->manifestPath;

        return $manifest;
    }

    /**
     * État courant de l'importmap de l'hôte : nom d'import => version (null si local).
     *
     * @return array<string, string|null>
     */
    private function presentEntries(): array
    {
        $present = [];
        foreach ($this->configReader->getEntries() as $entry) {
            $present[$entry->importName] = $entry->isRemotePackage() ? $entry->version : null;
        }

        return $present;
    }

    private function toRequireOptions(RequiredPackage $package): PackageRequireOptions
    {
        if ($package->isLocal()) {
            return new PackageRequireOptions(
                packageModuleSpecifier: $package->packageModuleSpecifier,
                importName: $package->importName,
                path: $package->localPath,
            );
        }

        return new PackageRequireOptions(
            packageModuleSpecifier: $package->packageModuleSpecifier,
            versionConstraint: $package->version,
            importName: $package->importName,
        );
    }
}
