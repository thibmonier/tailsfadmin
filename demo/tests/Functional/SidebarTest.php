<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-006 (sidebar responsive multi-niveaux).
 *
 * Couverture : câblage DOM côté serveur (attributs Stimulus, structure sidebar,
 * bouton hamburger, groupes de menu). Le comportement JS pur (collapse/drawer/
 * focus trap) requiert un navigateur réel.
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant l'implémentation du contrôleur sidebar et du MenuBuilder
 *   GREEN: passent après l'implémentation complète
 */
final class SidebarTest extends WebTestCase
{
    public function testSidebarHasStimulusDataController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller~="tailsfadmin--sidebar"]',
            'Le data-controller "tailsfadmin--sidebar" doit être présent dans le HTML rendu'
        );
    }

    public function testHamburgerButtonExistsInHeader(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $hamburger = $crawler->filter('[data-action*="tailsfadmin--sidebar#open"]');
        self::assertGreaterThan(
            0,
            $hamburger->count(),
            'Un bouton hamburger avec data-action "tailsfadmin--sidebar#open" doit exister dans le header'
        );
    }

    public function testSidebarHasNavElement(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $sidebarNav = $crawler->filter('aside.sidebar nav');
        self::assertGreaterThan(
            0,
            $sidebarNav->count(),
            'La sidebar doit contenir un élément <nav>'
        );
    }

    public function testSidebarContainsAtLeastOneMenuGroup(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $groupHeaders = $crawler->filter('aside.sidebar h3');
        self::assertGreaterThan(
            0,
            $groupHeaders->count(),
            'La sidebar doit contenir au moins un groupe de menu (h3)'
        );
    }

    public function testSidebarMenuItemsAreRendered(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $menuItems = $crawler->filter('aside.sidebar nav a');
        self::assertGreaterThan(
            0,
            $menuItems->count(),
            'La sidebar doit contenir au moins un lien de menu'
        );
    }
}
