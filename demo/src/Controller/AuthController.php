<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Pages d'authentification de la démo tailsfadmin — US-023.
 *
 * UI de démonstration UNIQUEMENT : aucune logique de sécurité réelle
 * (pas de SecurityBundle, pas d'authenticator). Les formulaires illustrent
 * le rendu ; ils ne traitent aucune soumission. Routes alignées sur le menu
 * (tailsfadmin.yaml : /auth/login, /auth/register) pour garder la sidebar cohérente.
 */
final class AuthController extends AbstractController
{
    #[Route('/auth/login', name: 'auth_login', methods: ['GET'])]
    public function signin(): Response
    {
        return $this->render('auth/signin.html.twig');
    }

    #[Route('/auth/register', name: 'auth_register', methods: ['GET'])]
    public function signup(): Response
    {
        return $this->render('auth/signup.html.twig');
    }
}
