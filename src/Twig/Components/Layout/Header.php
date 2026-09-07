<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Header statique du layout admin.
 *
 * Sprint 1 — statique (pas d'interactivité).
 * L'emplacement theme-toggle est reservé (US-005 Sprint 2).
 * L'interactivité (hamburger, dropdowns) viendra en US-007.
 */
#[AsTwigComponent('tsf:Layout:Header', template: '@Tailsfadmin/components/Layout/Header.html.twig')]
final class Header
{
}
