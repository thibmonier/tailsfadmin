<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * T-TECH-03 — Galerie UI Kit (/ui-kit).
 *
 * Page de vitrine des composants du Sprint 3 (US-008, US-009, US-010).
 * Sert de support de revue visuelle et de cible pour les tests fonctionnels.
 */
final class UiKitController extends AbstractController
{
    #[Route('/ui-kit', name: 'ui_kit')]
    public function index(): Response
    {
        return $this->render('ui-kit/index.html.twig');
    }
}
