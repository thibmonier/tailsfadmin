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

    // ─── US-034 : écrans auth/utilitaires étendus (UI de démo) ───────────────

    #[Route('/auth/reset-password', name: 'auth_reset', methods: ['GET'])]
    public function resetPassword(): Response
    {
        return $this->render('auth/reset-password.html.twig');
    }

    #[Route('/auth/new-password', name: 'auth_new_password', methods: ['GET'])]
    public function newPassword(): Response
    {
        return $this->render('auth/new-password.html.twig');
    }

    #[Route('/auth/otp', name: 'auth_otp', methods: ['GET'])]
    public function otp(): Response
    {
        return $this->render('auth/otp.html.twig');
    }

    #[Route('/auth/success', name: 'auth_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('auth/success.html.twig');
    }

    #[Route('/auth/maintenance', name: 'auth_maintenance', methods: ['GET'])]
    public function maintenance(): Response
    {
        return $this->render('auth/maintenance.html.twig');
    }

    #[Route('/auth/coming-soon', name: 'auth_coming_soon', methods: ['GET'])]
    public function comingSoon(): Response
    {
        return $this->render('auth/coming-soon.html.twig');
    }

    /** Vitrine de la page d'erreur 500 métier (rendu direct, sans lever d'exception). */
    #[Route('/auth/500', name: 'auth_error500', methods: ['GET'])]
    public function error500Preview(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error500.html.twig');
    }

    /**
     * Route de démonstration qui lève une exception, pour prouver que la page 500
     * métier s'affiche sans stack trace en production (kernel.debug=false).
     */
    #[Route('/_demo/boom', name: 'demo_boom', methods: ['GET'])]
    public function boom(): never
    {
        throw new \RuntimeException('Erreur serveur simulée (démo US-034).');
    }
}
