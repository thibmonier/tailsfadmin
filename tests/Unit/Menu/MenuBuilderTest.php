<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit\Menu;

use PHPUnit\Framework\TestCase;
use Tailsfadmin\Menu\MenuBuilder;
use Tailsfadmin\Menu\MenuGroup;
use Tailsfadmin\Menu\MenuItem;

/**
 * Tests unitaires TDD — US-006 (MenuBuilder service).
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant l'implémentation de MenuItem / MenuGroup / MenuBuilder
 *   GREEN: passent après implémentation complète
 */
final class MenuBuilderTest extends TestCase
{
    // ─── MenuItem ────────────────────────────────────────────────────────────

    public function testMenuItemHasLabel(): void
    {
        $item = new MenuItem(label: 'Dashboard', path: '/', icon: 'dashboard');

        self::assertSame('Dashboard', $item->label);
    }

    public function testMenuItemHasPath(): void
    {
        $item = new MenuItem(label: 'Dashboard', path: '/', icon: 'dashboard');

        self::assertSame('/', $item->path);
    }

    public function testMenuItemHasIcon(): void
    {
        $item = new MenuItem(label: 'Dashboard', path: '/', icon: 'dashboard');

        self::assertSame('dashboard', $item->icon);
    }

    public function testMenuItemHasNoChildrenByDefault(): void
    {
        $item = new MenuItem(label: 'Dashboard', path: '/', icon: 'dashboard');

        self::assertSame([], $item->children);
    }

    public function testMenuItemCanHaveChildren(): void
    {
        $child = new MenuItem(label: 'Sub Item', path: '/sub', icon: '');
        $item  = new MenuItem(
            label: 'Forms',
            path: '/forms',
            icon: 'forms',
            children: [$child],
        );

        self::assertCount(1, $item->children);
        self::assertSame('Sub Item', $item->children[0]->label);
    }

    // ─── MenuGroup ───────────────────────────────────────────────────────────

    public function testMenuGroupHasLabel(): void
    {
        $group = new MenuGroup(label: 'Menu', items: []);

        self::assertSame('Menu', $group->label);
    }

    public function testMenuGroupHasItems(): void
    {
        $item  = new MenuItem(label: 'Dashboard', path: '/', icon: 'dashboard');
        $group = new MenuGroup(label: 'Menu', items: [$item]);

        self::assertCount(1, $group->items);
    }

    // ─── MenuBuilder ─────────────────────────────────────────────────────────

    public function testMenuBuilderReturnsEmptyArrayWhenNoConfig(): void
    {
        $builder = new MenuBuilder([]);

        self::assertSame([], $builder->getGroups());
    }

    public function testMenuBuilderReturnsGroupsFromConfig(): void
    {
        $config = [
            [
                'group' => 'Menu',
                'items' => [
                    ['label' => 'Dashboard', 'path' => '/', 'icon' => 'dashboard', 'children' => []],
                ],
            ],
        ];

        $builder = new MenuBuilder($config);
        $groups  = $builder->getGroups();

        self::assertCount(1, $groups);
        self::assertInstanceOf(MenuGroup::class, $groups[0]);
        self::assertSame('Menu', $groups[0]->label);
    }

    public function testMenuBuilderCreatesItemsInGroup(): void
    {
        $config = [
            [
                'group' => 'Menu',
                'items' => [
                    ['label' => 'Dashboard', 'path' => '/', 'icon' => 'dashboard', 'children' => []],
                    ['label' => 'Calendrier', 'path' => '/calendar', 'icon' => 'calendar', 'children' => []],
                ],
            ],
        ];

        $builder = new MenuBuilder($config);
        $groups  = $builder->getGroups();

        self::assertCount(2, $groups[0]->items);
        self::assertInstanceOf(MenuItem::class, $groups[0]->items[0]);
        self::assertSame('Dashboard', $groups[0]->items[0]->label);
    }

    public function testMenuBuilderCreatesNestedChildren(): void
    {
        $config = [
            [
                'group' => 'Menu',
                'items' => [
                    [
                        'label'    => 'Forms',
                        'path'     => '/forms',
                        'icon'     => 'forms',
                        'children' => [
                            ['label' => 'Form Layout', 'path' => '/forms/layout', 'icon' => '', 'children' => []],
                        ],
                    ],
                ],
            ],
        ];

        $builder = new MenuBuilder($config);
        $groups  = $builder->getGroups();

        self::assertCount(1, $groups[0]->items[0]->children);
        self::assertSame('Form Layout', $groups[0]->items[0]->children[0]->label);
    }

    public function testMenuBuilderHandlesMultipleGroups(): void
    {
        $config = [
            [
                'group' => 'Menu',
                'items' => [
                    ['label' => 'Dashboard', 'path' => '/', 'icon' => 'dashboard', 'children' => []],
                ],
            ],
            [
                'group' => 'Others',
                'items' => [
                    ['label' => 'Charts', 'path' => '/charts', 'icon' => 'charts', 'children' => []],
                ],
            ],
        ];

        $builder = new MenuBuilder($config);
        $groups  = $builder->getGroups();

        self::assertCount(2, $groups);
        self::assertSame('Menu', $groups[0]->label);
        self::assertSame('Others', $groups[1]->label);
    }
}
