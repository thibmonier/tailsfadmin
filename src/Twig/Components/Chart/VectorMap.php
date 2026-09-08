<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Chart;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant VectorMap — tsf:Chart:VectorMap.
 *
 * US-019 : carte vectorielle du monde (jsvectormap) encapsulée par le contrôleur
 * Stimulus "tailsfadmin--vectormap" (ADR-004). Utile pour la démographie client.
 *
 * Props :
 *   - markers : liste de marqueurs { name, coords: [lat, lng] }
 *   - map     : nom de la carte (défaut "world")
 *   - height  : hauteur du conteneur en pixels (défaut 212)
 *
 * Les couleurs (régions, marqueurs, fond) et le dark-mode sont pilotés par les
 * classes `.jvm-*` de assets/styles/app.css, cohérentes avec le thème.
 */
#[AsTwigComponent('tsf:Chart:VectorMap', template: '@Tailsfadmin/components/Chart/VectorMap.html.twig')]
final class VectorMap
{
    /**
     * Marqueurs à afficher.
     *
     * @var array<int, array{name: string, coords: array{0: float, 1: float}}>
     */
    public array $markers = [];

    /** Nom de la carte enregistrée (jsvectormap). */
    public string $map = 'world';

    /** Hauteur du conteneur de carte, en pixels. */
    public int $height = 212;
}
