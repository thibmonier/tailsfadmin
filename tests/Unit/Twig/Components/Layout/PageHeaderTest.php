<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit\Twig\Components\Layout;

use PHPUnit\Framework\TestCase;
use Tailsfadmin\Twig\Components\Layout\PageHeader;

/**
 * US-087 / G4 — Composant PageHeader.
 */
final class PageHeaderTest extends TestCase
{
    public function testDefaults(): void
    {
        $header = new PageHeader();

        self::assertSame('', $header->title);
        self::assertNull($header->subtitle);
    }

    public function testAcceptsTitleAndSubtitle(): void
    {
        $header = new PageHeader();
        $header->title = 'Bonjour, Camille';
        $header->subtitle = 'Semaine 38';

        self::assertSame('Bonjour, Camille', $header->title);
        self::assertSame('Semaine 38', $header->subtitle);
    }
}
