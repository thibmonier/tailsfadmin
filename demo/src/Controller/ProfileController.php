<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Page profil de la démo tailsfadmin — US-022.
 *
 * Assemble carte profil, blocs infos/adresse et deux modales d'édition
 * (tsf:Ui:Modal + composants form). Règle d'isolation (ADR-002) : usage seul,
 * données de démo statiques, aucune persistance (les POST redirigent — PRG).
 */
final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        // POST (soumission d'une modale d'édition) : démo sans persistance.
        // Pattern Post/Redirect/Get pour éviter la re-soumission au refresh.
        if ($request->isMethod('POST')) {
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig', [
            'user' => [
                'firstName' => 'Thomas',
                'lastName' => 'Martin',
                'role' => 'Responsable produit',
                'location' => 'Lyon, France',
                'email' => 'thomas.martin@exemple.fr',
                'phone' => '+33 6 12 34 56 78',
                'bio' => 'Chef de produit passionné par les interfaces soignées et accessibles.',
                'social' => [
                    'facebook' => 'https://facebook.com/',
                    'x' => 'https://x.com/',
                    'linkedin' => 'https://linkedin.com/',
                    'instagram' => 'https://instagram.com/',
                ],
            ],
            'address' => [
                'country' => 'France',
                'cityState' => 'Lyon, Auvergne-Rhône-Alpes',
                'postalCode' => '69003',
                'taxId' => 'FR40123456789',
            ],
        ]);
    }
}
