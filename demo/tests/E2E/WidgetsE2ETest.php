<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

/**
 * T-TECH-01 — Tests Panther de montage réel des libs JS (Sprint 5).
 *
 * Ces tests prouvent que les contrôleurs Stimulus montent RÉELLEMENT
 * les bibliothèques tierces dans le navigateur, ce que les tests
 * fonctionnels WebTestCase ne peuvent pas vérifier (pas de JS).
 *
 * Suite : "e2e" → lancés via `composer test:e2e`, jamais par `composer test`.
 *
 * Tests :
 *   1. ApexCharts monté : attend que la lib injecte son SVG (.apexcharts-canvas).
 *   2. flatpickr ouvert : attend que le calendrier flatpickr s'affiche au clic.
 *   3. FullCalendar rendu : attend que la grille daygrid soit dans le DOM.
 */
final class WidgetsE2ETest extends PantherTestCase
{
    /**
     * Prouve qu'ApexCharts se monte réellement.
     *
     * ApexCharts injecte un élément .apexcharts-canvas dans le conteneur
     * après appel de chart.render(). Un test fonctionnel ne voit que l'attribut
     * data-controller, pas ce SVG. Ce test attend le SVG injecté.
     */
    public function testApexChartsInjectsSvgCanvas(): void
    {
        $client = static::createPantherClient([
            'browser' => static::CHROME,
        ]);

        $client->request('GET', '/ui-kit');

        // ApexCharts rend son SVG après JS ; on attend qu'il soit visible.
        $client->waitFor('[data-testid="chart-line-demo"] .apexcharts-canvas');

        self::assertSelectorExists(
            '[data-testid="chart-line-demo"] .apexcharts-canvas',
            'ApexCharts doit avoir injecté un .apexcharts-canvas dans la zone line-chart.'
        );

        // Vérification bonus : le SVG est bien dans le canvas
        self::assertSelectorExists(
            '[data-testid="chart-line-demo"] .apexcharts-canvas svg',
            'Le canvas ApexCharts doit contenir un élément SVG.'
        );
    }

    /**
     * Prouve que flatpickr s'ouvre au clic sur l'input datepicker.
     *
     * Le contrôleur tailsfadmin--datepicker appelle flatpickr(this.element, ...)
     * dans connect(). Un clic sur l'input doit ouvrir le calendrier flatpickr
     * (classe .flatpickr-calendar sur un div ajouté au body).
     */
    public function testFlatpickrOpensOnInputClick(): void
    {
        $client = static::createPantherClient([
            'browser' => static::CHROME,
        ]);

        $client->request('GET', '/ui-kit');

        // Clic sur l'input datepicker simple (zone single)
        $client->executeScript("
            document.querySelector('[data-testid=\"datepicker-single\"] input').click();
        ");

        // flatpickr ajoute .flatpickr-calendar (+ .open) sur le body quand il s'ouvre
        $client->waitFor('.flatpickr-calendar.open');

        self::assertSelectorExists(
            '.flatpickr-calendar.open',
            'flatpickr doit s\'ouvrir au clic et ajouter .flatpickr-calendar.open au DOM.'
        );
    }

    /**
     * Prouve que FullCalendar rend son interface (toolbar + grille).
     *
     * FullCalendar injecte une structure HTML complexe dans le conteneur cible.
     * La présence de .fc (root) et .fc-toolbar (barre de navigation prev/next/today)
     * prouve que le calendrier s'est correctement monté et rendu.
     */
    public function testFullCalendarRendersGrid(): void
    {
        $client = static::createPantherClient([
            'browser' => static::CHROME,
        ]);

        $client->request('GET', '/ui-kit');

        // FullCalendar injecte la classe .fc sur un div racine dans calendarElTarget,
        // puis .fc-toolbar pour la barre de navigation.
        $client->waitFor('[data-testid="calendar"] .fc-toolbar');

        self::assertSelectorExists(
            '[data-testid="calendar"] .fc-toolbar',
            'FullCalendar doit avoir rendu sa barre de navigation .fc-toolbar.'
        );

        // Le conteneur racine .fc est également présent
        self::assertSelectorExists(
            '[data-testid="calendar"] .fc',
            'FullCalendar doit avoir injecté un élément racine .fc dans le conteneur.'
        );
    }
}
