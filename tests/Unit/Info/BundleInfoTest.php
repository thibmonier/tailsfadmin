<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit\Info;

use PHPUnit\Framework\TestCase;
use Tailsfadmin\Info\BundleInfo;

/**
 * Test unitaire pour BundleInfo.
 *
 * Valide que le service d'information du bundle retourne les valeurs attendues.
 */
final class BundleInfoTest extends TestCase
{
    private BundleInfo $bundleInfo;

    protected function setUp(): void
    {
        $this->bundleInfo = new BundleInfo();
    }

    public function testNameReturnsTailsfadmin(): void
    {
        self::assertSame('tailsfadmin', $this->bundleInfo->name());
    }

    public function testVersionReturnsDevVersion(): void
    {
        self::assertStringContainsString('0.1.0', $this->bundleInfo->version());
    }
}
