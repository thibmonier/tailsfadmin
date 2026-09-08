<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de l'app hôte de test (US-030).
 *
 * Fournit les routes exigées par le layout admin du bundle (`home` pour le logo
 * de la sidebar, `locale_switch` pour le sélecteur de langue du header) et deux
 * pages de smoke : la page nominale (composants montés) et la page « chart » qui
 * dépend d'ApexCharts (sert au scénario d'échec de vendoring).
 */
final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/chart', name: 'chart')]
    public function chart(): Response
    {
        return $this->render('chart.html.twig');
    }

    #[Route('/switch-locale/{locale}', name: 'locale_switch', requirements: ['locale' => '[a-z]{2}'])]
    public function localeSwitch(string $locale): Response
    {
        // Route factice : le layout référence path('locale_switch'), pas besoin de
        // logique métier pour le smoke.
        return $this->redirectToRoute('home');
    }
}
