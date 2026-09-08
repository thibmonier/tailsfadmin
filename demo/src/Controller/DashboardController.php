<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Dashboard e-commerce de la démo tailsfadmin — US-021.
 *
 * Page phare : assemble les composants tsf existants (charts, cards, badges,
 * avatars, tables) en un back-office fidèle à TailAdmin. C'est le point d'entrée
 * de la démo (route `/`, cible du menu « Dashboard »).
 *
 * Règle d'isolation (ADR-002) : ce contrôleur ne fait QUE de l'usage.
 * Les données ci-dessous sont des fixtures de démonstration statiques ;
 * aucune logique réutilisable ne vit dans l'application de démo.
 */
final class DashboardController extends AbstractController
{
    /** Libellés des 12 derniers mois (axe des abscisses des graphiques). */
    private const MONTHS = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

    // Route nommée `home` : contrat du bundle (le logo de la sidebar lie `path('home')`).
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'metrics' => $this->metrics(),
            'target' => $this->monthlyTarget(),
            'salesSeries' => [['name' => 'Ventes', 'data' => [168, 385, 201, 298, 187, 195, 291, 110, 215, 390, 280, 112]]],
            'statsSeries' => [
                ['name' => 'Ventes', 'data' => [180, 190, 170, 160, 175, 165, 170, 205, 230, 210, 240, 235]],
                ['name' => 'Revenus', 'data' => [40, 30, 50, 40, 55, 40, 70, 100, 110, 120, 150, 140]],
            ],
            'months' => self::MONTHS,
            'orders' => $this->recentOrders(),
            'demographics' => $this->demographics(),
        ]);
    }

    /**
     * Tuiles KPI : valeur + variation (badge ↑/↓).
     *
     * @return list<array{label: string, value: string, delta: string, trend: 'up'|'down', icon: string}>
     */
    private function metrics(): array
    {
        return [
            ['label' => 'Clients', 'value' => '3 782', 'delta' => '+11,01 %', 'trend' => 'up', 'icon' => 'users'],
            ['label' => 'Commandes', 'value' => '5 359', 'delta' => '−9,05 %', 'trend' => 'down', 'icon' => 'cart'],
            ['label' => 'Revenus', 'value' => '45 231 €', 'delta' => '+12,40 %', 'trend' => 'up', 'icon' => 'revenue'],
            ['label' => 'Taux de conversion', 'value' => '3,48 %', 'delta' => '+2,10 %', 'trend' => 'up', 'icon' => 'target'],
        ];
    }

    /**
     * Objectif mensuel (jauge radiale).
     *
     * @return array{percent: float, delta: string, target: string, revenue: string, today: string}
     */
    private function monthlyTarget(): array
    {
        return [
            'percent' => 75.55,
            'delta' => '+10 %',
            'target' => '20 K€',
            'revenue' => '16 K€',
            'today' => '1,5 K€',
        ];
    }

    /**
     * Commandes récentes : produit (avatar), catégorie, prix, statut (badge).
     *
     * @return list<array{product: string, variant: string, category: string, price: string, status: 'delivered'|'pending'|'canceled'}>
     */
    private function recentOrders(): array
    {
        return [
            ['product' => 'MacBook Pro 14"', 'variant' => '2 variantes', 'category' => 'Portables', 'price' => '1 999 €', 'status' => 'delivered'],
            ['product' => 'Apple Watch Ultra', 'variant' => '1 variante', 'category' => 'Montres', 'price' => '879 €', 'status' => 'pending'],
            ['product' => 'iPhone 15 Pro Max', 'variant' => '2 variantes', 'category' => 'Smartphones', 'price' => '1 299 €', 'status' => 'delivered'],
            ['product' => 'iPad Pro 12.9"', 'variant' => '2 variantes', 'category' => 'Tablettes', 'price' => '1 149 €', 'status' => 'canceled'],
            ['product' => 'AirPods Pro', 'variant' => '1 variante', 'category' => 'Audio', 'price' => '279 €', 'status' => 'delivered'],
        ];
    }

    /**
     * Démographie client : répartition par pays (placeholder, US-019/jsvectormap au backlog).
     *
     * @return list<array{country: string, customers: string, percent: int}>
     */
    private function demographics(): array
    {
        return [
            ['country' => 'France', 'customers' => '2 379 clients', 'percent' => 79],
            ['country' => 'États-Unis', 'customers' => '1 245 clients', 'percent' => 42],
        ];
    }
}
