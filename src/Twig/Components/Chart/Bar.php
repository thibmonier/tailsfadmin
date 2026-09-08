<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Chart;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Bar Chart — tsf:Chart:Bar.
 *
 * US-018 : graphique en barres câblé au contrôleur Stimulus
 * "tailsfadmin--apexcharts" (ApexCharts v7 vendoré via importmap).
 * Aucun Alpine.js. Aucun CDN.
 *
 * Les données sont passées en JSON via data-*-value.
 * Le contrôleur instancie ApexCharts et observe le thème dark/light.
 *
 * Utilisation :
 *   <twig:tsf:Chart:Bar :series="myVar" />
 *   <twig:tsf:Chart:Bar
 *       :series="[{name:'Ventes',data:[168,385,201]}]"
 *       :categories="['Jan','Fév','Mar']"
 *       :height="180"
 *   />
 */
#[AsTwigComponent('tsf:Chart:Bar', template: '@Tailsfadmin/components/Chart/Bar.html.twig')]
final class Bar
{
    /**
     * Tableau de séries ApexCharts.
     * Chaque série = ['name' => string, 'data' => int[]].
     *
     * @var array<int, array{name: string, data: array<int, int|float>}>
     */
    public array $series = [];

    /**
     * Étiquettes de l'axe X.
     *
     * @var string[]
     */
    public array $categories = [];

    /** Hauteur du graphe en pixels. */
    public int $height = 180;

    /** Titre optionnel affiché au-dessus du graphe. */
    public string $title = '';
}
