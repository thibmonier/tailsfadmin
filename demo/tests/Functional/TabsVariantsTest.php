<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-036 (amélioration) — variantes du composant tsf:Ui:Tabs sur /ui-kit :
 *   - « segmented » : conteneur gris + pastille blanche active (style « Default » TailAdmin) ;
 *   - « underline » : soulignement de l'onglet actif ;
 *   - underline + icônes : icône rendue avant le libellé quand `item.icon` est défini.
 */
final class TabsVariantsTest extends WebTestCase
{
    public function testSegmentedVariantRendersGrayContainerAndWhitePill(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');
        self::assertResponseIsSuccessful();

        self::assertSelectorExists('[data-testid="tabs-segmented-demo"]');

        $tablist = $crawler->filter('[data-testid="tabs-segmented-demo"] [role="tablist"]');
        self::assertCount(1, $tablist);
        self::assertStringContainsString('bg-gray-100', $tablist->attr('class') ?? '', 'Le conteneur segmenté doit avoir un fond gris');

        // Un seul onglet actif au départ ; le surlignage est piloté par aria-selected
        // (variante Tailwind), donc TOUS les onglets portent la même classe et le
        // visuel suit la sélection sans intervention JS sur les classes.
        $active = $crawler->filter('[data-testid="tabs-segmented-demo"] [role="tab"][aria-selected="true"]');
        self::assertCount(1, $active);

        $tabs = $crawler->filter('[data-testid="tabs-segmented-demo"] [role="tab"]');
        $tabs->each(function ($tab): void {
            self::assertStringContainsString('aria-selected:bg-white', $tab->attr('class') ?? '', 'La pastille active doit être pilotée par aria-selected');
        });
    }

    public function testIconVariantRendersIconsInTabs(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $icons = $crawler->filter('[data-testid="tabs-icon-demo"] [role="tab"] svg');
        self::assertGreaterThanOrEqual(4, $icons->count(), 'Chaque onglet avec icône doit rendre un SVG');
    }

    public function testUnderlineVariantStillRenders(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        // Variante par défaut (underline) : conteneur avec bordure basse, sans fond gris.
        $tablist = $crawler->filter('[data-testid="tabs-demo"] [role="tablist"]');
        self::assertCount(1, $tablist);
        self::assertStringContainsString('border-b', $tablist->attr('class') ?? '');
        self::assertStringNotContainsString('bg-gray-100', $tablist->attr('class') ?? '');
    }
}
