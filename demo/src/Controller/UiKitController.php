<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\DemoContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * T-TECH-03 — Galerie UI Kit (/ui-kit).
 *
 * Page de vitrine des composants du Sprint 3/4 (US-008..US-014).
 * Sert de support de revue visuelle et de cible pour les tests fonctionnels.
 *
 * Route POST /ui-kit : traite le formulaire DemoContactType pour démontrer
 * le form theme TailAdmin avec les états d'erreur.
 */
final class UiKitController extends AbstractController
{
    #[Route('/ui-kit', name: 'ui_kit', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(DemoContactType::class);
        $form->handleRequest($request);

        return $this->render('ui-kit/index.html.twig', [
            'contactForm' => $form,
        ]);
    }
}
