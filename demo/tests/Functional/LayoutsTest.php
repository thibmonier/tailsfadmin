<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-037 — Tests fonctionnels : galerie des 6 variantes de layout (/layouts).
 *
 * Chaque variante étend le layout du bundle et surcharge des blocs (sans
 * dupliquer le shell). Les tests vérifient le rendu et la structure clé de
 * chaque variante (présence/absence de sidebar, nav horizontale, largeur boxed,
 * sous-barre, disposition inversée).
 */
final class LayoutsTest extends WebTestCase
{
    private const VARIANTS = ['default', 'mini-sidebar', 'horizontal', 'boxed', 'sidebar-right', 'double-header'];

    public function testIndexListsSixVariants(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/layouts');

        self::assertResponseIsSuccessful();
        self::assertCount(6, $crawler->filter('[data-testid="layout-card"]'));
    }

    public function testUnknownVariantReturns404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/layouts/ceci-nexiste-pas');

        self::assertResponseStatusCodeSame(404);
    }

    public function testEachVariantRenders(): void
    {
        $client = static::createClient();

        foreach (self::VARIANTS as $variant) {
            $crawler = $client->request('GET', '/layouts/' . $variant);
            self::assertResponseIsSuccessful();
            self::assertCount(
                1,
                $crawler->filter('[data-testid="layout-demo"][data-variant="' . $variant . '"]'),
                \sprintf('La variante « %s » doit rendre son contenu de démonstration', $variant),
            );
            // Le contenu de page est injecté sans dupliquer le chrome.
            self::assertLessThanOrEqual(1, $crawler->filter('header')->count());
        }
    }

    public function testDefaultVariantHasExpandableSidebar(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/layouts/default');

        self::assertCount(1, $crawler->filter('aside.sidebar'));
        self::assertStringContainsString('max-w-screen-2xl', $crawler->filter('#main-content')->attr('class') ?? '');
    }

    public function testMiniSidebarVariantRendersMiniClass(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/layouts/mini-sidebar');

        $aside = $crawler->filter('aside.sidebar');
        self::assertCount(1, $aside);
        self::assertStringContainsString('sidebar-mini', $aside->attr('class') ?? '');
        // Les libellés restent dans le DOM (accessibles), seulement masqués visuellement.
        self::assertGreaterThan(0, $crawler->filter('aside.sidebar .menu-item-text')->count());
    }

    public function testHorizontalVariantHasNavAndNoSidebar(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/layouts/horizontal');

        self::assertCount(0, $crawler->filter('aside.sidebar'), 'La variante horizontale ne doit pas avoir de sidebar');
        self::assertSelectorExists('[data-testid="horizontal-nav"]');
        self::assertGreaterThan(0, $crawler->filter('[data-testid="horizontal-nav"] a')->count());
    }

    public function testBoxedVariantConstrainsMainWidth(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/layouts/boxed');

        self::assertStringContainsString('max-w-5xl', $crawler->filter('#main-content')->attr('class') ?? '');
    }

    public function testSidebarRightVariantReversesShell(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/layouts/sidebar-right');

        self::assertCount(1, $crawler->filter('aside.sidebar'));
        self::assertGreaterThan(0, $crawler->filter('.flex-row-reverse')->count(), 'Le wrapper doit inverser la disposition');
    }

    public function testDoubleHeaderVariantHasSecondaryBar(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/layouts/double-header');

        self::assertSelectorExists('[data-testid="secondary-bar"]');
        // Un seul <header> (la sous-barre est un <div>, pas un second header).
        self::assertCount(1, $crawler->filter('header'));
    }
}
