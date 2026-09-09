<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit\Menu;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
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

    // ─── Filtrage par permission (is_granted) ────────────────────────────────

    public function testMenuItemHasNoPermissionByDefault(): void
    {
        $item = new MenuItem(label: 'Dashboard', path: '/', icon: 'dashboard');

        self::assertNull($item->permission);
    }

    public function testMenuItemCanHavePermission(): void
    {
        $item = new MenuItem(label: 'Admin', path: '/admin', icon: 'pages', permission: 'ROLE_ADMIN');

        self::assertSame('ROLE_ADMIN', $item->permission);
    }

    public function testMenuBuilderCarriesPermissionFromConfig(): void
    {
        $config = [
            ['group' => 'Menu', 'items' => [
                ['label' => 'Admin', 'path' => '/admin', 'icon' => 'pages', 'permission' => 'ROLE_ADMIN'],
            ]],
        ];

        $groups = (new MenuBuilder($config))->getGroups();

        self::assertSame('ROLE_ADMIN', $groups[0]->items[0]->permission);
    }

    public function testMenuBuilderWithoutCheckerShowsProtectedItems(): void
    {
        $config = [
            ['group' => 'Menu', 'items' => [
                ['label' => 'Admin', 'path' => '/admin', 'icon' => 'pages', 'permission' => 'ROLE_ADMIN'],
            ]],
        ];

        // Aucun checker injecté → rétro-compatible, tout est visible.
        $groups = (new MenuBuilder($config))->getGroups();

        self::assertCount(1, $groups[0]->items);
    }

    public function testMenuBuilderHidesItemWhenNotGranted(): void
    {
        $config = [
            ['group' => 'Menu', 'items' => [
                ['label' => 'Dashboard', 'path' => '/', 'icon' => 'dashboard'],
                ['label' => 'Admin', 'path' => '/admin', 'icon' => 'pages', 'permission' => 'ROLE_ADMIN'],
            ]],
        ];

        $groups = (new MenuBuilder($config, $this->checker([])))->getGroups();

        self::assertCount(1, $groups[0]->items);
        self::assertSame('Dashboard', $groups[0]->items[0]->label);
    }

    public function testMenuBuilderKeepsItemWhenGranted(): void
    {
        $config = [
            ['group' => 'Menu', 'items' => [
                ['label' => 'Admin', 'path' => '/admin', 'icon' => 'pages', 'permission' => 'ROLE_ADMIN'],
            ]],
        ];

        $groups = (new MenuBuilder($config, $this->checker(['ROLE_ADMIN'])))->getGroups();

        self::assertCount(1, $groups[0]->items);
    }

    public function testMenuBuilderHidesEmptyGroupAfterFiltering(): void
    {
        $config = [
            ['group' => 'Public', 'items' => [
                ['label' => 'Home', 'path' => '/', 'icon' => 'dashboard'],
            ]],
            ['group' => 'Admin', 'items' => [
                ['label' => 'Users', 'path' => '/users', 'icon' => 'pages', 'permission' => 'ROLE_ADMIN'],
            ]],
        ];

        $groups = (new MenuBuilder($config, $this->checker([])))->getGroups();

        self::assertCount(1, $groups);
        self::assertSame('Public', $groups[0]->label);
    }

    public function testMenuBuilderFiltersChildrenByPermission(): void
    {
        $config = [
            ['group' => 'Menu', 'items' => [[
                'label' => 'Reports', 'path' => '#', 'icon' => 'charts', 'children' => [
                    ['label' => 'Public', 'path' => '/r/pub', 'icon' => ''],
                    ['label' => 'Secret', 'path' => '/r/sec', 'icon' => '', 'permission' => 'ROLE_ADMIN'],
                ],
            ]]],
        ];

        $groups = (new MenuBuilder($config, $this->checker([])))->getGroups();

        self::assertCount(1, $groups[0]->items[0]->children);
        self::assertSame('Public', $groups[0]->items[0]->children[0]->label);
    }

    public function testMenuBuilderHidesPureParentWhenAllChildrenFiltered(): void
    {
        $config = [
            ['group' => 'Menu', 'items' => [
                ['label' => 'Home', 'path' => '/', 'icon' => 'dashboard'],
                ['label' => 'Admin', 'path' => '#', 'icon' => 'pages', 'children' => [
                    ['label' => 'Users', 'path' => '/users', 'icon' => '', 'permission' => 'ROLE_ADMIN'],
                ]],
            ]],
        ];

        $groups = (new MenuBuilder($config, $this->checker([])))->getGroups();

        self::assertCount(1, $groups[0]->items);
        self::assertSame('Home', $groups[0]->items[0]->label);
    }

    public function testMenuBuilderHidesProtectedItemWhenNoAuthToken(): void
    {
        $config = [
            ['group' => 'Menu', 'items' => [
                ['label' => 'Home', 'path' => '/', 'icon' => 'dashboard'],
                ['label' => 'Admin', 'path' => '/admin', 'icon' => 'pages', 'permission' => 'ROLE_ADMIN'],
            ]],
        ];

        // Hors contexte d'authentification, le checker lève une exception → item masqué.
        $groups = (new MenuBuilder($config, $this->throwingChecker()))->getGroups();

        self::assertCount(1, $groups[0]->items);
        self::assertSame('Home', $groups[0]->items[0]->label);
    }

    /**
     * Checker de test : n'autorise que les attributs de la liste blanche.
     *
     * Un mock PHPUnit est utilisé (plutôt qu'une classe anonyme) pour rester
     * compatible avec la signature de isGranted() de Symfony 7.3 comme 8.1.
     *
     * @param list<string> $granted
     */
    private function checker(array $granted): AuthorizationCheckerInterface
    {
        $checker = $this->createStub(AuthorizationCheckerInterface::class);
        // Callback variadique : accepte la signature de isGranted() quelle que soit
        // la version de Symfony (2 params en 7.3, 3 en 8.1) sans notice PHPUnit.
        $checker->method('isGranted')->willReturnCallback(
            static fn (mixed $attribute, mixed ...$args): bool => is_string($attribute) && in_array($attribute, $granted, true),
        );

        return $checker;
    }

    /**
     * Checker de test simulant l'absence de token d'authentification.
     */
    private function throwingChecker(): AuthorizationCheckerInterface
    {
        $checker = $this->createStub(AuthorizationCheckerInterface::class);
        $checker->method('isGranted')->willThrowException(new AuthenticationCredentialsNotFoundException());

        return $checker;
    }
}
