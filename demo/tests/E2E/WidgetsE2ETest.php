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
 *   2. flatpickr ouvert : CSS chargé + icône de taille bornée (<50px).
 *   3. FullCalendar rendu : toolbar + grille daygrid + cellules de jours.
 *   4. jsvectormap monté : SVG de carte + régions (US-019).
 */
final class WidgetsE2ETest extends PantherTestCase
{
    /**
     * Prouve que jsvectormap (US-019) se monte réellement sur le dashboard.
     *
     * jsvectormap injecte un <svg> contenant des chemins de régions
     * (.jvm-region) dans le conteneur de carte. Un test fonctionnel ne voit
     * que l'attribut data-controller ; ce test attend le SVG rendu par la lib.
     */
    public function testVectorMapMountsOnDashboard(): void
    {
        $client = static::createPantherClient([
            'browser' => static::CHROME,
        ]);

        $client->request('GET', '/');

        // jsvectormap dessine son SVG après JS ; on attend une région rendue.
        $client->waitFor('[data-testid="chart-vectormap"] svg .jvm-region');

        self::assertSelectorExists(
            '[data-testid="chart-vectormap"] svg',
            'jsvectormap doit avoir injecté un SVG dans le conteneur de carte.'
        );

        $regionCount = $client->executeScript(
            'return document.querySelectorAll(\'[data-testid="chart-vectormap"] .jvm-region\').length;'
        );
        self::assertGreaterThan(
            50,
            $regionCount,
            sprintf('La carte du monde doit rendre de nombreuses régions (rendu : %d).', (int) $regionCount)
        );
    }
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
     * Prouve que flatpickr s'ouvre au clic ET que le CSS flatpickr est chargé.
     *
     * Le contrôleur tailsfadmin--datepicker importe flatpickr/dist/flatpickr.min.css
     * depuis connect(). Sans ce CSS, les icônes SVG de navigation (prev/next month)
     * s'affichent à 1372×1372px (chevron géant). Ce test valide que le CSS est actif
     * en vérifiant que l'icône .flatpickr-next-month svg reste sous 50px de hauteur.
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

        // Assertion durcie : le CSS flatpickr doit borner la taille de l'icône SVG.
        // Sans CSS, le chevron .flatpickr-next-month svg mesure ~1372×1372px.
        // Avec CSS, il doit être ≤ 50px (taille de l'élément parent .flatpickr-next-month).
        $iconHeight = $client->executeScript("
            const svg = document.querySelector('.flatpickr-calendar.open .flatpickr-next-month svg');
            if (!svg) return null;
            return Math.round(svg.getBoundingClientRect().height);
        ");

        self::assertNotNull(
            $iconHeight,
            'L\'icône .flatpickr-next-month svg doit exister dans le calendrier ouvert.'
        );
        self::assertLessThan(
            50,
            $iconHeight,
            sprintf(
                'Le CSS flatpickr doit borner l\'icône SVG (hauteur actuelle : %dpx, attendu < 50px).'
                . ' Un chevron géant indique que le CSS flatpickr n\'est pas chargé.',
                (int) $iconHeight,
            )
        );
    }

    /**
     * Prouve que FullCalendar rend son interface complète : toolbar + grille + cellules.
     *
     * FullCalendar v6 injecte son CSS via JS (<style data-fullcalendar>) et rend
     * le calendrier dans le calendarElTarget. La présence de :
     *   - .fc-toolbar : barre de navigation prev/next/today
     *   - .fc-scrollgrid : table principale de la grille
     *   - .fc-daygrid-day : cellules de jours individuelles
     * prouve que le calendrier s'est monté et rendu correctement.
     */
    public function testFullCalendarRendersGrid(): void
    {
        $client = static::createPantherClient([
            'browser' => static::CHROME,
        ]);

        $client->request('GET', '/ui-kit');

        // FullCalendar injecte .fc-toolbar puis la grille. On attend la toolbar d'abord.
        $client->waitFor('[data-testid="calendar"] .fc-toolbar');

        self::assertSelectorExists(
            '[data-testid="calendar"] .fc-toolbar',
            'FullCalendar doit avoir rendu sa barre de navigation .fc-toolbar.'
        );

        // Faire défiler le calendrier dans la fenêtre visible et forcer un recalcul
        // de taille. FullCalendar v6 utilise un ResizeObserver : si le conteneur est
        // hors écran lors du montage, les dimensions peuvent être incorrectes et la
        // grille ne se monte pas. Le scroll + resize déclenche updateSize().
        $client->executeScript("
            document.querySelector('[data-testid=\"calendar\"]').scrollIntoView({behavior: 'instant', block: 'center'});
            window.dispatchEvent(new Event('resize'));
        ");

        // Assertion durcie : la grille (.fc-scrollgrid) doit être dans le DOM.
        // Sans le plugin dayGrid ou en cas d'erreur de montage, seule la toolbar
        // peut apparaître sans la grille.
        $client->waitFor('[data-testid="calendar"] .fc-scrollgrid');

        self::assertSelectorExists(
            '[data-testid="calendar"] .fc-scrollgrid',
            'FullCalendar doit avoir rendu la table principale .fc-scrollgrid (grille daygrid).'
        );

        // Assertion durcie : des cellules de jours individuelles doivent être présentes.
        // L\'absence de .fc-daygrid-day indique que la vue dayGridMonth ne s\'est pas montée.
        self::assertSelectorExists(
            '[data-testid="calendar"] .fc-daygrid-day',
            'FullCalendar doit avoir rendu au moins une cellule .fc-daygrid-day.'
        );
    }
}
