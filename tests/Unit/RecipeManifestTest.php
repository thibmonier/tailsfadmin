<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Gardes anti-dérive sur la recette Flex (US-029).
 *
 * La recette (`recipe/`, export-ignore) n'est pas distribuée dans le package :
 * elle est destinée à `symfony/recipes-contrib`. Ce test vérifie qu'elle reste
 * cohérente avec le bundle (FQCN, commande d'install, path AssetMapper) — il ne
 * s'exécute que dans le dépôt de dev/CI, où `recipe/` existe.
 */
final class RecipeManifestTest extends TestCase
{
    private const RECIPE = __DIR__ . '/../../recipe/tailsfadmin/tailsfadmin-bundle/1.0';

    public function testManifestIsValidJsonAndRegistersTheBundle(): void
    {
        /** @var array<string, mixed> $manifest */
        $manifest = json_decode(
            (string) file_get_contents(self::RECIPE . '/manifest.json'),
            true,
            512,
            \JSON_THROW_ON_ERROR,
        );

        self::assertArrayHasKey('bundles', $manifest);
        self::assertIsArray($manifest['bundles']);
        self::assertArrayHasKey('Tailsfadmin\\TailsfadminBundle', $manifest['bundles']);
        self::assertSame(['all'], $manifest['bundles']['Tailsfadmin\\TailsfadminBundle']);
    }

    public function testPostInstallMentionsTheAssetsSteps(): void
    {
        $json = (string) file_get_contents(self::RECIPE . '/manifest.json');

        self::assertStringContainsString('tailsfadmin:assets:install', $json);
        self::assertStringContainsString('theme.css', $json);
    }

    public function testCopiedConfigFilesExist(): void
    {
        self::assertFileExists(self::RECIPE . '/config/packages/tailsfadmin.yaml');
        self::assertFileExists(self::RECIPE . '/config/packages/tailsfadmin_assets.yaml');
    }

    public function testAssetsConfigDeclaresTheBundleControllerPaths(): void
    {
        $yaml = (string) file_get_contents(self::RECIPE . '/config/packages/tailsfadmin_assets.yaml');

        self::assertStringContainsString("assets/controllers': 'bundles/tailsfadmin'", $yaml);
        self::assertStringContainsString("assets/vendor-src': 'bundles/tailsfadmin-vendor'", $yaml);
    }
}
