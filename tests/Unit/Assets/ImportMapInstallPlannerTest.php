<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit\Assets;

use PHPUnit\Framework\TestCase;
use Tailsfadmin\Assets\ImportMapInstallPlanner;
use Tailsfadmin\Assets\RequiredPackage;

/**
 * Tests unitaires TDD — US-027 / T-027-03 (planner d'installation d'assets).
 *
 * Le planner est PUR : il décide quelles entrées du manifeste doivent être
 * requises, lesquelles sont déjà satisfaites (idempotence) et lesquelles sont
 * en conflit de version — sans dépendre d'AssetMapper (cf. ADR-007). La commande
 * `tailsfadmin:assets:install` traduit ensuite le plan en appels AssetMapper.
 *
 * Cycle RED → GREEN : écrits avant l'implémentation du planner.
 */
final class ImportMapInstallPlannerTest extends TestCase
{
    /** @var array<string, array<string, mixed>> */
    private const MANIFEST = [
        'apexcharts' => ['version' => '7.1.0'],
        'flatpickr/dist/flatpickr.min.css' => ['version' => '4.6.13', 'type' => 'css'],
        'fullcalendar' => ['local' => 'assets/vendor-src/fullcalendar/fullcalendar-esm.js'],
        'jsvectormap/dist/maps/world.js' => ['version' => '1.7.0', 'package_specifier' => 'jsvectormap/dist/maps/world.js'],
    ];

    private ImportMapInstallPlanner $planner;

    protected function setUp(): void
    {
        $this->planner = new ImportMapInstallPlanner();
    }

    public function testFreshInstallRequiresEveryManifestEntry(): void
    {
        $plan = $this->planner->plan(self::MANIFEST, presentEntries: [], force: false);

        self::assertCount(4, $plan->toRequire);
        self::assertSame([], $plan->skipped);
        self::assertSame([], $plan->conflicts);
        self::assertFalse($plan->hasConflicts());
    }

    public function testRemoteEntryCarriesVersionAndSpecifier(): void
    {
        $plan = $this->planner->plan(['apexcharts' => ['version' => '7.1.0']], [], false);

        $pkg = $plan->toRequire[0];
        self::assertInstanceOf(RequiredPackage::class, $pkg);
        self::assertSame('apexcharts', $pkg->importName);
        self::assertSame('apexcharts', $pkg->packageModuleSpecifier);
        self::assertSame('7.1.0', $pkg->version);
        self::assertNull($pkg->localPath);
        self::assertFalse($pkg->isLocal());
    }

    public function testPackageSpecifierDefaultsToImportName(): void
    {
        $plan = $this->planner->plan(['apexcharts/core' => ['version' => '7.1.0']], [], false);

        self::assertSame('apexcharts/core', $plan->toRequire[0]->packageModuleSpecifier);
    }

    public function testExplicitPackageSpecifierIsHonored(): void
    {
        $manifest = ['world' => ['version' => '1.7.0', 'package_specifier' => 'jsvectormap/dist/maps/world.js']];

        $plan = $this->planner->plan($manifest, [], false);

        self::assertSame('world', $plan->toRequire[0]->importName);
        self::assertSame('jsvectormap/dist/maps/world.js', $plan->toRequire[0]->packageModuleSpecifier);
    }

    public function testLocalEntryCarriesPathAndNoVersion(): void
    {
        $plan = $this->planner->plan(
            ['fullcalendar' => ['local' => 'assets/vendor-src/fullcalendar/fullcalendar-esm.js']],
            [],
            false,
        );

        $pkg = $plan->toRequire[0];
        self::assertTrue($pkg->isLocal());
        self::assertSame('assets/vendor-src/fullcalendar/fullcalendar-esm.js', $pkg->localPath);
        self::assertNull($pkg->version);
    }

    public function testEntryAlreadyPresentWithSameVersionIsSkipped(): void
    {
        $plan = $this->planner->plan(
            ['apexcharts' => ['version' => '7.1.0']],
            presentEntries: ['apexcharts' => '7.1.0'],
            force: false,
        );

        self::assertSame([], $plan->toRequire);
        self::assertSame(['apexcharts'], $plan->skipped);
        self::assertSame([], $plan->conflicts);
    }

    public function testPresentLocalEntryIsSkipped(): void
    {
        $plan = $this->planner->plan(
            ['fullcalendar' => ['local' => 'assets/vendor-src/fullcalendar/fullcalendar-esm.js']],
            presentEntries: ['fullcalendar' => null],
            force: false,
        );

        self::assertSame([], $plan->toRequire);
        self::assertSame(['fullcalendar'], $plan->skipped);
    }

    public function testVersionMismatchWithoutForceIsAConflict(): void
    {
        $plan = $this->planner->plan(
            ['apexcharts' => ['version' => '7.1.0']],
            presentEntries: ['apexcharts' => '6.0.0'],
            force: false,
        );

        self::assertSame([], $plan->toRequire);
        self::assertSame([], $plan->skipped);
        self::assertTrue($plan->hasConflicts());
        self::assertCount(1, $plan->conflicts);
        self::assertSame('apexcharts', $plan->conflicts[0]->importName);
        self::assertSame('7.1.0', $plan->conflicts[0]->wantedVersion);
        self::assertSame('6.0.0', $plan->conflicts[0]->presentVersion);
    }

    public function testVersionMismatchWithForceIsRequired(): void
    {
        $plan = $this->planner->plan(
            ['apexcharts' => ['version' => '7.1.0']],
            presentEntries: ['apexcharts' => '6.0.0'],
            force: true,
        );

        self::assertCount(1, $plan->toRequire);
        self::assertSame('apexcharts', $plan->toRequire[0]->importName);
        self::assertSame([], $plan->conflicts);
    }
}
