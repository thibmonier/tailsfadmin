<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-032 — Tests fonctionnels : dashboards métier (Analytics, Marketing, CRM, SaaS).
 *
 * Assemblage de composants existants (KPI, charts ApexCharts, tables, Badge).
 * Données statiques (ADR-002). Le montage réel des charts est couvert par l'E2E existant.
 */
final class DashboardsTest extends WebTestCase
{
    /** @return iterable<string, array{string}> */
    public static function dashboards(): iterable
    {
        yield 'analytics' => ['/dashboards/analytics'];
        yield 'marketing' => ['/dashboards/marketing'];
        yield 'crm' => ['/dashboards/crm'];
        yield 'saas' => ['/dashboards/saas'];
    }

    #[DataProvider('dashboards')]
    public function testDashboardReturns200AndAdminLayout(string $route): void
    {
        $client = static::createClient();
        $client->request('GET', $route);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'tailsfadmin');
    }

    #[DataProvider('dashboards')]
    public function testDashboardHasFourKpisAndAtLeastOneChart(string $route): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $route);

        self::assertCount(4, $crawler->filter('[data-testid="kpi-card"]'), 'Chaque dashboard expose 4 KPI');
        self::assertGreaterThanOrEqual(
            1,
            $crawler->filter('[data-controller="tailsfadmin--apexcharts"]')->count(),
            'Chaque dashboard monte au moins un graphique',
        );
    }

    public function testAnalyticsRendersDataTable(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/dashboards/analytics');

        self::assertGreaterThanOrEqual(1, $crawler->filter('table')->count());
    }

    public function testCrmTableShowsStatusBadges(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/dashboards/crm');

        self::assertGreaterThanOrEqual(1, $crawler->filter('[data-testid="crm-deals"] table')->count());
        self::assertGreaterThan(0, $crawler->filter('[data-testid="crm-deals"] .inline-flex')->count());
    }

    public function testIndexListsFourDashboards(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/dashboards');

        self::assertResponseIsSuccessful();
        self::assertCount(4, $crawler->filter('[data-testid="dashboard-card"]'));
    }
}
