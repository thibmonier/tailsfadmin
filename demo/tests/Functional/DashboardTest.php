<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel — US-021 : Dashboard e-commerce (assemblage).
 *
 * Vérifie que la page d'accueil (`/`) assemble bien les sections attendues :
 * métriques KPI, jauge d'objectif mensuel (radialBar), graphiques ApexCharts
 * (ventes + statistiques), table des commandes récentes et démographie client.
 *
 * La revue VISUELLE (screenshot clair + dark) reste le filet de sécurité
 * principal (leçon Sprint 5) ; ces assertions garantissent le câblage.
 */
final class DashboardTest extends WebTestCase
{
    public function testDashboardReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
    }

    public function testDashboardRendersAdminLayout(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        // Le layout admin du bundle affiche la marque dans la sidebar/header.
        self::assertSelectorTextContains('body', 'tailsfadmin');
    }

    public function testDashboardShowsKpiMetrics(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertSelectorTextContains('body', 'Clients');
        self::assertSelectorTextContains('body', 'Commandes');
        self::assertSelectorTextContains('body', 'Revenus');
    }

    public function testDashboardShowsMonthlyTargetGauge(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertSelectorTextContains('body', 'Objectif mensuel');
        // Jauge radiale câblée sur le contrôleur ApexCharts existant.
        self::assertStringContainsString('radialBar', (string) $client->getResponse()->getContent());
    }

    public function testDashboardWiresApexcharts(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Au moins 3 conteneurs graphiques : ventes (bar), statistiques (area), objectif (radialBar).
        $charts = $crawler->filter('[data-controller="tailsfadmin--apexcharts"]');
        self::assertGreaterThanOrEqual(3, $charts->count());
    }

    public function testDashboardShowsRecentOrdersTable(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertSelectorTextContains('body', 'Commandes récentes');
        self::assertGreaterThanOrEqual(1, $crawler->filter('table')->count());
        // Statut de commande rendu via tsf:Ui:Badge.
        self::assertSelectorTextContains('body', 'Livré');
    }

    public function testDashboardShowsCustomerDemographics(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertSelectorTextContains('body', 'Démographie');
    }

    public function testDashboardWiresVectorMap(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Carte jsvectormap (US-019) câblée sur son contrôleur Stimulus.
        self::assertGreaterThanOrEqual(1, $crawler->filter('[data-controller="tailsfadmin--vectormap"]')->count());
        // Les marqueurs (pays clients) sont sérialisés dans la value.
        self::assertStringContainsString('France', (string) $client->getResponse()->getContent());
    }
}
