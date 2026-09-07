<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Preloader avec contrôleur Stimulus.
 *
 * Le preloader est visible au chargement et masqué au window.load
 * via le contrôleur Stimulus "tailsfadmin--preloader".
 * Remplace l'implémentation Alpine.js originale (ADR-004).
 */
#[AsTwigComponent('tsf:Ui:Preloader', template: '@Tailsfadmin/components/Ui/Preloader.html.twig')]
final class Preloader
{
}
