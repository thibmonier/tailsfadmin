<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Pages utilitaires de la démo tailsfadmin — US-023.
 *
 * Page vierge : gabarit minimal héritant du layout admin, point de départ
 * pour de nouvelles pages. Route alignée sur le menu (/pages/blank).
 */
final class PagesController extends AbstractController
{
    #[Route('/pages/blank', name: 'pages_blank', methods: ['GET'])]
    public function blank(): Response
    {
        return $this->render('pages/blank.html.twig');
    }
}
