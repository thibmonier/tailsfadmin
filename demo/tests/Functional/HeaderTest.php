<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-007 (header : recherche + dropdowns).
 *
 * Couverture : câblage DOM côté serveur (attributs Stimulus, ARIA, structure).
 * Le comportement clavier pur (Cmd/Ctrl+K, flèches) requiert un navigateur réel.
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant implémentation des contrôleurs search + dropdown
 *   GREEN: passent après refonte du Header
 *
 * Non-régression US-005 et US-006 :
 *   - theme-toggle (tailsfadmin--theme) toujours présent
 *   - hamburger (data-action sidebar#open) toujours présent
 */
final class HeaderTest extends WebTestCase
{
    // ─── Contrôleur search ───────────────────────────────────────────────────

    public function testSearchControllerDataAttributePresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller~="tailsfadmin--search"]',
            'Le data-controller "tailsfadmin--search" doit être présent dans le header'
        );
    }

    public function testSearchInputExists(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $searchInputs = $crawler->filter('input[type="search"], input[type="text"][placeholder]');
        self::assertGreaterThan(
            0,
            $searchInputs->count(),
            'Un champ de recherche doit être présent dans le header'
        );
    }

    public function testSearchInputHasDataTarget(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $inputs = $crawler->filter('[data-tailsfadmin--search-target="input"]');
        self::assertGreaterThan(
            0,
            $inputs->count(),
            'Le champ de recherche doit avoir data-tailsfadmin--search-target="input"'
        );
    }

    // ─── Contrôleur dropdown ─────────────────────────────────────────────────

    public function testDropdownControllerPresentForUserMenu(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller~="tailsfadmin--dropdown"]',
            'Au moins un data-controller "tailsfadmin--dropdown" doit être présent (user-menu ou notifications)'
        );
    }

    public function testDropdownTriggersHaveAriaExpanded(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $triggers = $crawler->filter('[data-controller~="tailsfadmin--dropdown"] [aria-expanded]');
        self::assertGreaterThan(
            0,
            $triggers->count(),
            'Les boutons déclencheurs de dropdown doivent avoir aria-expanded'
        );
    }

    public function testDropdownMenuHasRoleMenu(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $menus = $crawler->filter('[role="menu"]');
        self::assertGreaterThan(
            0,
            $menus->count(),
            'Au moins un élément avec role="menu" doit être présent pour les dropdowns'
        );
    }

    public function testDropdownMenuItemsHaveRoleMenuItem(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $menuItems = $crawler->filter('[role="menuitem"]');
        self::assertGreaterThan(
            0,
            $menuItems->count(),
            'Les items de dropdown doivent avoir role="menuitem"'
        );
    }

    // ─── Non-régression US-005 (theme-toggle) ────────────────────────────────

    public function testThemeToggleStillPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller~="tailsfadmin--theme"]',
            'Le bouton theme-toggle (US-005) doit toujours être présent'
        );
    }

    // ─── Non-régression US-006 (hamburger sidebar) ───────────────────────────

    public function testHamburgerSidebarStillPresent(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $hamburger = $crawler->filter('[data-action*="tailsfadmin--sidebar#open"]');
        self::assertGreaterThan(
            0,
            $hamburger->count(),
            'Le bouton hamburger (US-006) doit toujours être présent et câblé sidebar#open'
        );
    }
}
