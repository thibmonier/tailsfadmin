<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Breadcrumb du layout admin.
 *
 * @param string $pageName Titre de la page courante (affiché en h2 et dernier item)
 */
#[AsTwigComponent('tsf:Layout:Breadcrumb', template: '@Tailsfadmin/components/Layout/Breadcrumb.html.twig')]
final class Breadcrumb
{
    public string $pageName = '';
}
