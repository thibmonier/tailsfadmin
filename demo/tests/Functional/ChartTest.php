<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-018 — Tests fonctionnels des composants Chart (ApexCharts).
 *
 * Vérifie :
 *   - La section graphiques est présente (#section-charts)
 *   - Les conteneurs ont data-controller="tailsfadmin--apexcharts"
 *   - Les values JSON sont présentes et décodables
 *   - Les trois graphiques (line, bar, dashboard) sont rendus
 *   - L'ancre de navigation est présente (T-TECH-01)
 * Note : le rendu SVG réel est couvert par les tests Panther (T-TECH-01).
 */
final class ChartTest extends WebTestCase
{
    /** La galerie /ui-kit rend la section graphiques. */
    public function testSectionChartsIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="section-charts"]');
    }

    /** Au moins un conteneur avec data-controller="tailsfadmin--apexcharts" est rendu. */
    public function testChartContainerHasDataController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-controller="tailsfadmin--apexcharts"]');
    }

    /** Le graphe Line est rendu (data-testid="chart-line"). */
    public function testLineChartIsRendered(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="chart-line"]');
        self::assertSelectorExists(
            '[data-testid="chart-line"] [data-controller="tailsfadmin--apexcharts"]'
        );
    }

    /** Le graphe Bar est rendu (data-testid="chart-bar"). */
    public function testBarChartIsRendered(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="chart-bar"]');
        self::assertSelectorExists(
            '[data-testid="chart-bar"] [data-controller="tailsfadmin--apexcharts"]'
        );
    }

    /** Le type-value "area" est transmis au contrôleur du Line Chart. */
    public function testLineChartTypeValueIsArea(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--apexcharts-type-value="area"]'
        );
    }

    /** Le type-value "bar" est transmis au contrôleur du Bar Chart. */
    public function testBarChartTypeValueIsBar(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--apexcharts-type-value="bar"]'
        );
    }

    /** La value series-value est du JSON valide (décodable). */
    public function testSeriesValueIsValidJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $html    = (string) $client->getResponse()->getContent();
        $crawler = $client->getCrawler();

        $containers = $crawler->filter('[data-tailsfadmin--apexcharts-series-value]');
        self::assertGreaterThan(0, $containers->count(), 'Au moins un conteneur avec series-value attendu.');

        $containers->each(function ($node) {
            $seriesJson = $node->attr('data-tailsfadmin--apexcharts-series-value');
            self::assertNotNull($seriesJson);
            $decoded = json_decode((string) $seriesJson, true);
            self::assertNotNull($decoded, "series-value doit être du JSON valide : {$seriesJson}");
            self::assertIsArray($decoded, 'series doit être un tableau JSON.');
        });
    }

    /** La value options-value est du JSON valide (décodable). */
    public function testOptionsValueIsValidJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $crawler    = $client->getCrawler();
        $containers = $crawler->filter('[data-tailsfadmin--apexcharts-options-value]');
        self::assertGreaterThan(0, $containers->count(), 'Au moins un conteneur avec options-value attendu.');

        $containers->each(function ($node) {
            $optionsJson = $node->attr('data-tailsfadmin--apexcharts-options-value');
            self::assertNotNull($optionsJson);
            $decoded = json_decode((string) $optionsJson, true);
            self::assertNotNull($decoded, "options-value doit être du JSON valide : {$optionsJson}");
            self::assertIsArray($decoded, 'options doit être un tableau/objet JSON.');
        });
    }

    /** La value height-value est un entier positif. */
    public function testHeightValueIsPositiveInteger(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $crawler    = $client->getCrawler();
        $containers = $crawler->filter('[data-tailsfadmin--apexcharts-height-value]');
        self::assertGreaterThan(0, $containers->count());

        $containers->each(function ($node) {
            $height = (int) $node->attr('data-tailsfadmin--apexcharts-height-value');
            self::assertGreaterThan(0, $height, 'height-value doit être un entier positif.');
        });
    }

    /** Les trois zones de démo graphiques sont présentes. */
    public function testAllThreeDemoZonesArePresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="chart-line-demo"]');
        self::assertSelectorExists('[data-testid="chart-bar-demo"]');
        self::assertSelectorExists('[data-testid="chart-dashboard-demo"]');
    }

    /** L'ancre #section-charts est présente dans la navigation (T-TECH-01). */
    public function testNavAnchorIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('a[href="#section-charts"]');
    }
}
