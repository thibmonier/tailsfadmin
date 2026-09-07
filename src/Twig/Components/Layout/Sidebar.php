<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Sidebar statique du layout admin.
 *
 * Sprint 1 — statique (sections MENU / OTHERS, classes ltr:/rtl: en place).
 * L'interactivité (collapsed, sous-menus) viendra en US-006.
 */
#[AsTwigComponent('tsf:Layout:Sidebar', template: '@Tailsfadmin/components/Layout/Sidebar.html.twig')]
final class Sidebar
{
}
