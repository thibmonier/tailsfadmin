<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Functional;

use PHPUnit\Framework\TestCase;

/**
 * Tests fonctionnels du câblage des assets — US-027 / T-027-06.
 *
 * Gardes anti-régression sur la consommabilité :
 *  1. le manifeste des pins (config/importmap-entries.php) déclare bien toutes
 *     les libs JS tierces attendues (ADR-007) ;
 *  2. CHAQUE contrôleur de assets/controllers/ est déclaré dans
 *     assets/package.json (footgun US-019 : sinon StimulusBundle lève
 *     « controller does not exist in the package ») ;
 *  3. les deux package.json (racine ↔ assets) restent synchronisés (T-027-05).
 */
final class AssetsWiringTest extends TestCase
{
    private const BUNDLE_DIR = __DIR__ . '/../..';

    public function testManifestDeclaresEveryThirdPartyLib(): void
    {
        $manifest = require self::BUNDLE_DIR . '/config/importmap-entries.php';

        self::assertIsArray($manifest);
        foreach (
            [
                'apexcharts',
                'apexcharts/core',
                'flatpickr',
                'flatpickr/dist/flatpickr.min.css',
                'dropzone',
                'dropzone/dist/dropzone.css',
                'fullcalendar/index.global.min.js',
                'fullcalendar',
                'jsvectormap',
                'jsvectormap/dist/maps/world.js',
                'jsvectormap/dist/jsvectormap.min.css',
            ] as $importName
        ) {
            self::assertArrayHasKey($importName, $manifest, \sprintf('Entrée « %s » absente du manifeste.', $importName));
        }
    }

    public function testFullCalendarIsALocalShimAndOthersAreVersioned(): void
    {
        $manifest = require self::BUNDLE_DIR . '/config/importmap-entries.php';

        self::assertArrayHasKey('local', $manifest['fullcalendar'], 'Le shim FullCalendar doit être une entrée locale.');
        self::assertArrayHasKey('version', $manifest['apexcharts'], 'ApexCharts doit être piné par version.');
        self::assertSame('7.1.0', $manifest['apexcharts']['version']);
    }

    public function testManifestExcludesHostOwnedEntries(): void
    {
        $manifest = require self::BUNDLE_DIR . '/config/importmap-entries.php';

        // Ces entrées appartiennent au squelette/StimulusBundle de l'hôte, pas au bundle.
        foreach (['app', '@hotwired/stimulus', '@symfony/stimulus-bundle'] as $hostEntry) {
            self::assertArrayNotHasKey($hostEntry, $manifest);
        }
    }

    public function testEveryControllerIsDeclaredInAssetsPackageJson(): void
    {
        $declared = $this->controllersFrom(self::BUNDLE_DIR . '/assets/package.json');

        foreach ($this->controllerFiles() as $file) {
            $key = $this->controllerKey($file);
            self::assertArrayHasKey(
                $key,
                $declared,
                \sprintf('Contrôleur « %s » non déclaré dans assets/package.json (footgun US-019).', $key),
            );
            self::assertSame('tailsfadmin--' . $key, $declared[$key]['name']);
            self::assertSame('controllers/' . $file, $declared[$key]['main']);
        }
    }

    public function testRootAndAssetsPackageJsonListTheSameControllers(): void
    {
        $assets = array_keys($this->controllersFrom(self::BUNDLE_DIR . '/assets/package.json'));
        $root = array_keys($this->controllersFrom(self::BUNDLE_DIR . '/package.json'));

        sort($assets);
        sort($root);

        self::assertSame($assets, $root, 'package.json racine et assets/package.json listent des contrôleurs différents (T-027-05).');
    }

    /**
     * @return array<string, array{name: string, main: string}>
     */
    private function controllersFrom(string $packageJsonPath): array
    {
        $json = file_get_contents($packageJsonPath);
        self::assertIsString($json);

        $data = json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
        self::assertIsArray($data);

        /** @var array<string, array{name: string, main: string}> $controllers */
        $controllers = $data['symfony']['controllers'] ?? [];

        return $controllers;
    }

    /**
     * @return list<string> noms de fichiers *_controller.js
     */
    private function controllerFiles(): array
    {
        $files = glob(self::BUNDLE_DIR . '/assets/controllers/*_controller.js');
        self::assertIsArray($files);
        self::assertNotEmpty($files);

        return array_map(static fn (string $path): string => basename($path), $files);
    }

    /**
     * apexcharts_controller.js → apexcharts ; alert_dismiss_controller.js → alert-dismiss.
     */
    private function controllerKey(string $fileName): string
    {
        $base = str_replace('_controller.js', '', $fileName);

        return str_replace('_', '-', $base);
    }
}
