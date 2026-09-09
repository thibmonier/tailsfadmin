<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * US-032 — Dashboards métier de démonstration (Analytics, Marketing, CRM, SaaS).
 *
 * Assemblage pur de composants existants (KPI inline, tsf:Chart:*, tables,
 * tsf:Ui:Badge / ProgressBar / Ribbon). Données STRICTEMENT statiques (ADR-002).
 *
 * @phpstan-type Kpi array{label:string, value:string, delta:string, trend:string, icon:string}
 */
final class DashboardsController extends AbstractController
{
    /** @var array<string, array{label:string, desc:string}> */
    private const DASHBOARDS = [
        'analytics' => ['label' => 'Analytics', 'desc' => 'Audience, sessions, sources et pages.'],
        'marketing' => ['label' => 'Marketing', 'desc' => 'Campagnes, ROI et budget par canal.'],
        'crm' => ['label' => 'CRM', 'desc' => 'Pipeline, deals et taux de conversion.'],
        'saas' => ['label' => 'SaaS', 'desc' => 'MRR, churn, ARPU et cohortes.'],
    ];

    private const MONTHS = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

    #[Route('/dashboards', name: 'dashboards', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('dashboards/index.html.twig', ['dashboards' => self::DASHBOARDS]);
    }

    #[Route('/dashboards/analytics', name: 'dashboard_analytics', methods: ['GET'])]
    public function analytics(): Response
    {
        return $this->render('dashboards/analytics.html.twig', [
            'meta' => self::DASHBOARDS['analytics'],
            'kpis' => [
                ['label' => 'Sessions', 'value' => '24 803', 'delta' => '+12,4 %', 'trend' => 'up', 'icon' => 'chart'],
                ['label' => 'Utilisateurs', 'value' => '18 214', 'delta' => '+8,1 %', 'trend' => 'up', 'icon' => 'users'],
                ['label' => 'Taux de rebond', 'value' => '42,3 %', 'delta' => '−3,2 %', 'trend' => 'up', 'icon' => 'eye'],
                ['label' => 'Durée moyenne', 'value' => '3 m 12 s', 'delta' => '+5,6 %', 'trend' => 'up', 'icon' => 'clock'],
            ],
            'sessionsSeries' => [['name' => 'Sessions', 'data' => [1650, 1720, 1980, 2100, 2050, 2300, 2450, 2380, 2600, 2720, 2680, 2803]]],
            'sourcesSeries' => [['name' => 'Visiteurs', 'data' => [8200, 5400, 3100, 2300, 1200]]],
            'sources' => ['Recherche', 'Direct', 'Social', 'Référents', 'E-mail'],
            'topPages' => [
                ['page' => '/', 'views' => '12 480', 'rate' => '38 %'],
                ['page' => '/tarifs', 'views' => '6 210', 'rate' => '22 %'],
                ['page' => '/blog/guide-symfony', 'views' => '4 870', 'rate' => '54 %'],
                ['page' => '/contact', 'views' => '3 120', 'rate' => '12 %'],
            ],
        ]);
    }

    #[Route('/dashboards/marketing', name: 'dashboard_marketing', methods: ['GET'])]
    public function marketing(): Response
    {
        return $this->render('dashboards/marketing.html.twig', [
            'meta' => self::DASHBOARDS['marketing'],
            'kpis' => [
                ['label' => 'Impressions', 'value' => '1,2 M', 'delta' => '+18,0 %', 'trend' => 'up', 'icon' => 'eye'],
                ['label' => 'Clics', 'value' => '48 900', 'delta' => '+9,4 %', 'trend' => 'up', 'icon' => 'cursor'],
                ['label' => 'CTR', 'value' => '4,07 %', 'delta' => '−0,6 %', 'trend' => 'down', 'icon' => 'chart'],
                ['label' => 'ROI', 'value' => '312 %', 'delta' => '+24,0 %', 'trend' => 'up', 'icon' => 'revenue'],
            ],
            'campaignSeries' => [['name' => 'Clics', 'data' => [4200, 6800, 5100, 7300, 8100, 6900]]],
            'campaignLabels' => ['Soldes', 'Été', 'Rentrée', 'Black Friday', 'Noël', 'Nouvel An'],
            'channels' => [
                ['label' => 'Search Ads', 'value' => 82, 'variant' => 'brand'],
                ['label' => 'Social Ads', 'value' => 64, 'variant' => 'success'],
                ['label' => 'E-mailing', 'value' => 47, 'variant' => 'warning'],
                ['label' => 'Display', 'value' => 28, 'variant' => 'error'],
            ],
        ]);
    }

    #[Route('/dashboards/crm', name: 'dashboard_crm', methods: ['GET'])]
    public function crm(): Response
    {
        return $this->render('dashboards/crm.html.twig', [
            'meta' => self::DASHBOARDS['crm'],
            'kpis' => [
                ['label' => 'Leads', 'value' => '1 284', 'delta' => '+6,2 %', 'trend' => 'up', 'icon' => 'users'],
                ['label' => 'Deals ouverts', 'value' => '142', 'delta' => '+11,0 %', 'trend' => 'up', 'icon' => 'briefcase'],
                ['label' => 'Taux conversion', 'value' => '24,8 %', 'delta' => '+2,1 %', 'trend' => 'up', 'icon' => 'chart'],
                ['label' => 'CA pipeline', 'value' => '486 k€', 'delta' => '−4,3 %', 'trend' => 'down', 'icon' => 'revenue'],
            ],
            'pipelineSeries' => [['name' => 'Pipeline (k€)', 'data' => [320, 360, 410, 390, 450, 486]]],
            'pipelineLabels' => \array_slice(self::MONTHS, 0, 6),
            'deals' => [
                ['company' => 'Acme Corp', 'amount' => '48 k€', 'stage' => 'won'],
                ['company' => 'Globex', 'amount' => '32 k€', 'stage' => 'negotiation'],
                ['company' => 'Initech', 'amount' => '21 k€', 'stage' => 'proposal'],
                ['company' => 'Umbrella', 'amount' => '12 k€', 'stage' => 'lost'],
            ],
        ]);
    }

    #[Route('/dashboards/saas', name: 'dashboard_saas', methods: ['GET'])]
    public function saas(): Response
    {
        return $this->render('dashboards/saas.html.twig', [
            'meta' => self::DASHBOARDS['saas'],
            'kpis' => [
                ['label' => 'MRR', 'value' => '52 400 €', 'delta' => '+7,8 %', 'trend' => 'up', 'icon' => 'revenue'],
                ['label' => 'Churn', 'value' => '2,1 %', 'delta' => '−0,4 %', 'trend' => 'up', 'icon' => 'refresh'],
                ['label' => 'Clients actifs', 'value' => '1 934', 'delta' => '+5,2 %', 'trend' => 'up', 'icon' => 'users'],
                ['label' => 'ARPU', 'value' => '27,1 €', 'delta' => '+1,9 %', 'trend' => 'up', 'icon' => 'chart'],
            ],
            'mrrSeries' => [['name' => 'MRR (k€)', 'data' => [31, 34, 37, 39, 42, 44, 46, 47, 49, 50, 51, 52]]],
            'months' => self::MONTHS,
            'targetPercent' => 78,
            'cohorts' => [
                ['label' => 'Cohorte Jan', 'value' => 92, 'variant' => 'success'],
                ['label' => 'Cohorte Fév', 'value' => 84, 'variant' => 'success'],
                ['label' => 'Cohorte Mar', 'value' => 71, 'variant' => 'brand'],
                ['label' => 'Cohorte Avr', 'value' => 63, 'variant' => 'warning'],
            ],
        ]);
    }
}
