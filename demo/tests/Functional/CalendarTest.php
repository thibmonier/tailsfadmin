<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-020 — Tests fonctionnels du composant Calendrier (FullCalendar).
 *
 * Vérifie :
 *   - La section calendrier est présente (#section-calendar)
 *   - Le conteneur a data-controller="tailsfadmin--calendar"
 *   - La value events est du JSON valide et décodable
 *   - La modale d'événement est présente (role="dialog")
 *   - Les cibles du contrôleur calendar sont présentes dans la modale
 *   - L'ancre de navigation est présente (T-TECH-01)
 * Note : les interactions (clic date/événement, ouverture modale) sont
 * couvertes par les tests Panther (T-TECH-01).
 */
final class CalendarTest extends WebTestCase
{
    /** La galerie /ui-kit rend la section calendrier. */
    public function testSectionCalendarIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="section-calendar"]');
    }

    /** Le conteneur a bien le data-controller Stimulus. */
    public function testCalendarContainerHasDataController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-controller="tailsfadmin--calendar"]');
    }

    /** Le composant rend un conteneur avec data-testid="calendar". */
    public function testCalendarTestIdIsRendered(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="calendar"]');
    }

    /** La cible calendarEl est présente (FullCalendar y injecte son HTML). */
    public function testCalendarElTargetIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-tailsfadmin--calendar-target="calendarEl"]');
    }

    /** La value events est du JSON valide et décodable. */
    public function testEventsValueIsValidJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $crawler    = $client->getCrawler();
        $containers = $crawler->filter('[data-tailsfadmin--calendar-events-value]');
        self::assertGreaterThan(0, $containers->count(), 'Au moins un conteneur calendar avec events-value attendu.');

        $containers->each(function ($node) {
            $eventsJson = $node->attr('data-tailsfadmin--calendar-events-value');
            self::assertNotNull($eventsJson);
            $decoded = json_decode((string) $eventsJson, true);
            self::assertNotNull($decoded, "events-value doit être du JSON valide : {$eventsJson}");
            self::assertIsArray($decoded, 'events doit être un tableau JSON.');
        });
    }

    /** Les événements de démo ont bien title et start. */
    public function testEventsContainExpectedFields(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $crawler    = $client->getCrawler();
        $container  = $crawler->filter('[data-tailsfadmin--calendar-events-value]')->first();
        $eventsJson = $container->attr('data-tailsfadmin--calendar-events-value');

        self::assertNotNull($eventsJson);
        $events = json_decode((string) $eventsJson, true);
        self::assertIsArray($events);
        self::assertGreaterThan(0, count($events), 'Au moins un événement attendu dans la démo.');

        foreach ($events as $event) {
            self::assertArrayHasKey('title', $event, 'Chaque événement doit avoir un "title".');
            self::assertArrayHasKey('start', $event, 'Chaque événement doit avoir un "start".');
        }
    }

    /** La modale d'événement est présente (role="dialog"). */
    public function testEventModalIsPresentWithRoleDialog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        // La modale tsf:Ui:Modal rend role="dialog" sur son panel
        self::assertSelectorExists('[data-testid="calendar"] [role="dialog"]');
    }

    /** La cible eventTitle du contrôleur calendar est dans la modale. */
    public function testEventTitleTargetIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-tailsfadmin--calendar-target="eventTitle"]');
    }

    /** La cible eventStart du contrôleur calendar est dans la modale. */
    public function testEventStartTargetIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-tailsfadmin--calendar-target="eventStart"]');
    }

    /** La cible eventEnd du contrôleur calendar est dans la modale. */
    public function testEventEndTargetIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-tailsfadmin--calendar-target="eventEnd"]');
    }

    /** La value initial-view est transmise au contrôleur. */
    public function testInitialViewValueIsTransmitted(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--calendar-initial-view-value="dayGridMonth"]'
        );
    }

    /** L'ancre #section-calendar est présente dans la navigation (T-TECH-01). */
    public function testNavAnchorIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('a[href="#section-calendar"]');
    }
}
