<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * US-033 — Pages type applicatives de démonstration (Settings, Pricing, Invoice,
 * Chat, File manager, Inbox). Assemblage de composants existants, données statiques.
 */
final class AppPagesController extends AbstractController
{
    #[Route('/app/settings', name: 'app_settings', methods: ['GET'])]
    public function settings(): Response
    {
        return $this->render('app/settings.html.twig');
    }

    #[Route('/app/pricing', name: 'app_pricing', methods: ['GET'])]
    public function pricing(): Response
    {
        return $this->render('app/pricing.html.twig', [
            'plans' => [
                ['name' => 'Starter', 'price' => '9 €', 'popular' => false, 'features' => ['1 projet', '5 Go de stockage', 'Support e-mail']],
                ['name' => 'Pro', 'price' => '29 €', 'popular' => true, 'features' => ['Projets illimités', '100 Go de stockage', 'Support prioritaire', 'Analytics avancés']],
                ['name' => 'Entreprise', 'price' => '99 €', 'popular' => false, 'features' => ['Tout Pro', 'SSO / SAML', 'SLA 99,9 %', 'Account manager']],
            ],
        ]);
    }

    #[Route('/app/invoice', name: 'app_invoice', methods: ['GET'])]
    public function invoice(): Response
    {
        return $this->render('app/invoice.html.twig', [
            'invoice' => [
                'number' => 'INV-2026-0042',
                'date' => '09/09/2026',
                'due' => '09/10/2026',
                'from' => 'tailsfadmin SAS',
                'to' => 'Acme Corp',
                'lines' => [
                    ['label' => 'Licence Pro (annuel)', 'qty' => 1, 'unit' => '290,00 €', 'total' => '290,00 €'],
                    ['label' => 'Utilisateurs additionnels', 'qty' => 5, 'unit' => '12,00 €', 'total' => '60,00 €'],
                    ['label' => 'Support prioritaire', 'qty' => 1, 'unit' => '90,00 €', 'total' => '90,00 €'],
                ],
                'subtotal' => '440,00 €',
                'tax' => '88,00 €',
                'total' => '528,00 €',
            ],
        ]);
    }

    #[Route('/app/chat', name: 'app_chat', methods: ['GET'])]
    public function chat(): Response
    {
        return $this->render('app/chat.html.twig', [
            'conversations' => [
                ['name' => 'Alice Dupont', 'last' => 'Parfait, merci !', 'time' => '09:24', 'unread' => 0, 'active' => true],
                ['name' => 'Bob Martin', 'last' => 'Je regarde ça aujourd\'hui.', 'time' => '08:57', 'unread' => 2, 'active' => false],
                ['name' => 'Carol Denis', 'last' => 'On se cale un point demain ?', 'time' => 'Hier', 'unread' => 0, 'active' => false],
            ],
            'messages' => [
                ['from' => 'them', 'text' => 'Salut ! Tu as pu voir la maquette ?'],
                ['from' => 'me', 'text' => 'Oui, c\'est top. Je valide.'],
                ['from' => 'them', 'text' => 'Parfait, merci !'],
            ],
        ]);
    }

    #[Route('/app/files', name: 'app_files', methods: ['GET'])]
    public function files(): Response
    {
        return $this->render('app/files.html.twig', [
            'files' => [
                ['name' => 'rapport-annuel.pdf', 'size' => '2,4 Mo', 'type' => 'pdf'],
                ['name' => 'logo.svg', 'size' => '18 Ko', 'type' => 'image'],
                ['name' => 'budget-2026.xlsx', 'size' => '512 Ko', 'type' => 'sheet'],
                ['name' => 'demo.mp4', 'size' => '48 Mo', 'type' => 'video'],
                ['name' => 'notes.txt', 'size' => '4 Ko', 'type' => 'text'],
                ['name' => 'archive.zip', 'size' => '120 Mo', 'type' => 'archive'],
            ],
        ]);
    }

    #[Route('/app/inbox', name: 'app_inbox', methods: ['GET'])]
    public function inbox(): Response
    {
        return $this->render('app/inbox.html.twig', [
            'emails' => [
                ['from' => 'Alice Dupont', 'subject' => 'Compte-rendu de réunion', 'preview' => 'Voici les points abordés ce matin…', 'time' => '09:24', 'unread' => true, 'active' => true],
                ['from' => 'Facturation', 'subject' => 'Votre facture INV-2026-0042', 'preview' => 'Merci pour votre confiance. Vous trouverez…', 'time' => '08:10', 'unread' => true, 'active' => false],
                ['from' => 'Bob Martin', 'subject' => 'RE: déploiement staging', 'preview' => 'C\'est en ligne, tu peux tester.', 'time' => 'Hier', 'unread' => false, 'active' => false],
                ['from' => 'Newsletter Symfony', 'subject' => 'A Week of Symfony', 'preview' => 'Les nouveautés de la semaine…', 'time' => 'Lun', 'unread' => false, 'active' => false],
            ],
        ]);
    }
}
