<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels — US-036 (tsf:Ui:ProgressBar, tsf:Ui:Ribbon, tsf:Ui:Tabs).
 *
 * Vérifie le rendu DOM des trois composants d'affichage sur /ui-kit (structure
 * ARIA, dégradation gracieuse du clamp ProgressBar, onglet actif par défaut).
 */
final class DisplayComponentsTest extends WebTestCase
{
    // ─── ProgressBar ─────────────────────────────────────────────────────────

    public function testProgressBarRendersWithAriaAndValue(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[role="progressbar"][aria-valuenow="72"][aria-valuemin="0"][aria-valuemax="100"]',
            'La ProgressBar doit exposer role=progressbar + aria-valuenow/min/max',
        );
    }

    public function testProgressBarValueIsClampedTo100(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        // value=140 → borné à 100 (dégradation gracieuse)
        self::assertSelectorExists(
            '[role="progressbar"][aria-valuenow="100"]',
            'Une valeur > 100 doit être bornée à 100 (aria-valuenow="100")',
        );
    }

    // ─── Ribbon ──────────────────────────────────────────────────────────────

    public function testRibbonsRender(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        $ribbonText = $crawler->filter('[data-testid="ribbon-demo"]')->text();
        self::assertStringContainsString('Nouveau', $ribbonText);
        self::assertStringContainsString('Promo', $ribbonText);
    }

    // ─── Tabs ────────────────────────────────────────────────────────────────

    public function testTabsRenderTablistWithThreeTabs(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[role="tablist"]', 'Les Tabs doivent rendre un role=tablist');
        self::assertCount(
            3,
            $crawler->filter('[data-testid="tabs-demo"] [role="tab"]'),
            'La démo Tabs doit contenir 3 onglets',
        );
    }

    public function testFirstTabIsActiveAndOthersHidden(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        // 1er onglet sélectionné
        self::assertSelectorExists(
            '[data-testid="tabs-demo"] [role="tab"][data-tab-id="profil"][aria-selected="true"]',
            'Le premier onglet (profil) doit être actif par défaut',
        );
        // panneau actif visible, panneau inactif masqué
        self::assertSelectorExists('[data-testid="tab-panel-profil"]');
        self::assertSelectorExists(
            '[role="tabpanel"][hidden]',
            'Au moins un panneau inactif doit porter l\'attribut hidden',
        );
    }
}
