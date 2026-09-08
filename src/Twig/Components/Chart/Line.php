<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Chart;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Line Chart — tsf:Chart:Line.
 *
 * US-018 : graphique en courbes/aires câblé au contrôleur Stimulus
 * "tailsfadmin--apexcharts" (ApexCharts v7 vendoré via importmap).
 * Aucun Alpine.js. Aucun CDN.
 *
 * Les données sont passées en JSON via data-*-value.
 * Le contrôleur instancie ApexCharts et observe le thème dark/light.
 *
 * Utilisation :
 *   <twig:tsf:Chart:Line :series="myVar" />
 *   <twig:tsf:Chart:Line
 *       :series="[{name:'Ventes',data:[10,20,30]}]"
 *       :categories="['Jan','Fév','Mar']"
 *       :height="250"
 *       type="area"
 *   />
 */
#[AsTwigComponent('tsf:Chart:Line', template: '@Tailsfadmin/components/Chart/Line.html.twig')]
final class Line
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
    public int $height = 310;

    /** Type ApexCharts : "line" ou "area". */
    public string $type = 'line';

    /** Titre optionnel affiché au-dessus du graphe. */
    public string $title = '';
}
