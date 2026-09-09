<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Tailsfadmin\Menu\MenuBuilder;

/**
 * US-037 — Galerie de variantes d'agencement (layout) du shell admin.
 *
 * Chaque variante étend `@Tailsfadmin/layout/admin.html.twig` et surcharge des
 * blocs (sidebar, header, layout_shell_class, main_class) — aucun shell dupliqué.
 */
final class LayoutsController extends AbstractController
{
    /** @var array<string, array{label:string, desc:string}> */
    private const VARIANTS = [
        'default' => ['label' => 'Sidebar extensible', 'desc' => 'Agencement par défaut : sidebar rétractable à gauche.'],
        'mini-sidebar' => ['label' => 'Mini-sidebar', 'desc' => 'Sidebar réduite aux icônes ; libellés accessibles (sr-only).'],
        'horizontal' => ['label' => 'Navigation horizontale', 'desc' => 'Menu principal dans l\'en-tête, sans sidebar.'],
        'boxed' => ['label' => 'Contenu boxed', 'desc' => 'Contenu centré à largeur maximale réduite.'],
        'sidebar-right' => ['label' => 'Sidebar à droite', 'desc' => 'Disposition inversée, adaptée au RTL.'],
        'double-header' => ['label' => 'En-tête double niveau', 'desc' => 'Barre principale + sous-barre d\'actions.'],
    ];

    #[Route('/layouts', name: 'layouts', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('layouts/index.html.twig', [
            'variants' => self::VARIANTS,
        ]);
    }

    #[Route('/layouts/{variant}', name: 'layouts_show', methods: ['GET'], requirements: ['variant' => '[a-z-]+'])]
    public function show(string $variant, MenuBuilder $menuBuilder): Response
    {
        if (!\array_key_exists($variant, self::VARIANTS)) {
            throw $this->createNotFoundException(\sprintf('Variante de layout « %s » inconnue.', $variant));
        }

        return $this->render('layouts/' . $variant . '.html.twig', [
            'variants' => self::VARIANTS,
            'current' => $variant,
            'meta' => self::VARIANTS[$variant],
            'menuGroups' => $menuBuilder->getGroups(),
        ]);
    }
}
