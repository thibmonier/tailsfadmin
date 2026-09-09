<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\AccountSettingsType;
use App\Form\DemoContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * US-038 — Page « Mise en page de formulaire ».
 *
 * Présente des gabarits de formulaire cohérents et accessibles réutilisant le
 * form theme du bundle et tsf:Ui:Card, sans introduire de nouveau widget :
 *   - gabarit une colonne (DemoContactType, réutilisé) ;
 *   - gabarit sectionné en grille deux colonnes avec barre d'actions
 *     (AccountSettingsType), soumis pour illustrer l'état d'erreur.
 */
final class FormsController extends AbstractController
{
    #[Route('/forms/layout', name: 'forms_layout', methods: ['GET', 'POST'])]
    public function layout(Request $request): Response
    {
        $accountForm = $this->createForm(AccountSettingsType::class);
        $accountForm->handleRequest($request);

        $status = Response::HTTP_OK;
        if ($accountForm->isSubmitted() && !$accountForm->isValid()) {
            // 422 sur soumission invalide (RFC 9110), cohérent avec le form theme demo.
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        return $this->render('forms/layout.html.twig', [
            'contactForm' => $this->createForm(DemoContactType::class),
            'accountForm' => $accountForm,
        ], new Response(status: $status));
    }
}
