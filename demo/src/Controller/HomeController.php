<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de la page d'accueil de la démo tailsfadmin.
 *
 * Règle d'isolation (ADR-002) : ce contrôleur ne contient que de l'usage.
 * Aucun code réutilisable ne doit vivre dans l'application de démo.
 */
final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
