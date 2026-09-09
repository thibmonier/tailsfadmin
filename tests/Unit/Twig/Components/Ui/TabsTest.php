<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit\Twig\Components\Ui;

use PHPUnit\Framework\TestCase;
use Tailsfadmin\Twig\Components\Ui\Tabs;

/**
 * US-036 / T-036-05 — Composant Tabs.
 *
 * `activeId()` retourne l'onglet actif s'il existe, sinon retombe sur le premier
 * (dégradation gracieuse), et null si aucun item.
 */
final class TabsTest extends TestCase
{
    public function testActiveIdReturnsTheRequestedTabWhenItExists(): void
    {
        $tabs = new Tabs();
        $tabs->items = [['id' => 'a', 'label' => 'Profil'], ['id' => 'b', 'label' => 'Sécurité']];
        $tabs->active = 'b';

        self::assertSame('b', $tabs->activeId());
    }

    public function testActiveIdFallsBackToFirstWhenActiveIsInvalid(): void
    {
        $tabs = new Tabs();
        $tabs->items = [['id' => 'a', 'label' => 'Profil'], ['id' => 'b', 'label' => 'Sécurité']];
        $tabs->active = 'z';

        self::assertSame('a', $tabs->activeId());
    }

    public function testActiveIdFallsBackToFirstWhenActiveIsNull(): void
    {
        $tabs = new Tabs();
        $tabs->items = [['id' => 'a', 'label' => 'Profil'], ['id' => 'b', 'label' => 'Sécurité']];

        self::assertSame('a', $tabs->activeId());
    }

    public function testActiveIdIsNullWhenNoItems(): void
    {
        $tabs = new Tabs();

        self::assertNull($tabs->activeId());
        self::assertSame('underline', $tabs->variant);
    }
}
