<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-004 (layout admin de base).
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant la création du layout et des composants
 *   GREEN: passent après l'implémentation complète (T-004-01 à T-004-05)
 *
 * DoD US-004 :
 *   - GET / : header, sidebar, main#main-content, preloader, breadcrumb, skip-link
 *   - Une page fille injecte son contenu sans dupliquer le chrome
 */
final class LayoutTest extends WebTestCase
{
    public function testHomePageHasHeaderElement(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'header',
            'La page doit contenir un élément <header> (composant tsf:Layout:Header)'
        );
    }

    public function testHomePageHasSidebarElement(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'aside.sidebar',
            'La page doit contenir un <aside class="sidebar"> (composant tsf:Layout:Sidebar)'
        );
    }

    public function testHomePageHasMainContentElement(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'main#main-content',
            'La page doit contenir un <main id="main-content">'
        );
    }

    public function testHomePageHasPreloaderElement(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller]',
            'La page doit contenir un élément avec un contrôleur Stimulus (preloader)'
        );
    }

    public function testHomePageHasSkipLink(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'a[href="#main-content"]',
            'La page doit contenir un skip-link vers #main-content (accessibilité)'
        );
    }

    public function testHomePageHasBreadcrumbWithDashboard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains(
            'nav[aria-label="breadcrumb"]',
            'Dashboard',
            'Le breadcrumb doit contenir "Dashboard"'
        );
    }

    public function testChildPageInjectsContentWithoutDuplicatingChrome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        // Le chrome (header, sidebar) n'apparaît qu'une fois
        self::assertCount(1, $crawler->filter('header'), 'Le <header> ne doit apparaître qu\'une fois');
        self::assertCount(1, $crawler->filter('aside.sidebar'), 'Le <aside.sidebar> ne doit apparaître qu\'une fois');
        self::assertCount(1, $crawler->filter('main#main-content'), 'Le <main#main-content> ne doit apparaître qu\'une fois');
    }

    public function testPreloaderControllerDataAttributeExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        // Le data-controller du preloader est présent dans le DOM
        $preloaderElement = $crawler->filter('[data-controller*="preloader"]');
        self::assertGreaterThan(
            0,
            $preloaderElement->count(),
            'Un élément avec data-controller contenant "preloader" doit être présent dans le DOM'
        );
    }
}
