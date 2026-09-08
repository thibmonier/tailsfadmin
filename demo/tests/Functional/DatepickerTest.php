<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-015 — Tests fonctionnels : Datepicker (flatpickr en Stimulus).
 *
 * Vérifie via /ui-kit que :
 *   - L'input datepicker est rendu avec data-controller="tailsfadmin--datepicker".
 *   - Les options sont transmises via data-*-value.
 *   - Le mode range est correctement configuré.
 *   - Le composant est désactivable.
 *
 * Note : le comportement d'ouverture réel du calendrier (interaction navigateur)
 * sera couvert par Panther en T-TECH-01.
 */
final class DatepickerTest extends WebTestCase
{
    public function testDatepickerSingleRendersStimulusController(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $singleWrapper = $crawler->filter('[data-testid="datepicker-single"]');
        self::assertGreaterThan(0, $singleWrapper->count(), 'Le bloc datepicker-single doit être présent');

        $input = $singleWrapper->filter('input[data-controller="tailsfadmin--datepicker"]');
        self::assertCount(1, $input, 'L\'input doit avoir data-controller="tailsfadmin--datepicker"');
    }

    public function testDatepickerSingleHasModeValue(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $input = $crawler->filter('[data-testid="datepicker-single"] input[data-controller="tailsfadmin--datepicker"]');
        self::assertCount(1, $input);

        $mode = $input->attr('data-tailsfadmin--datepicker-mode-value');
        self::assertSame('single', $mode, 'Le mode doit être "single" pour le datepicker simple');
    }

    public function testDatepickerRangeHasModeRangeValue(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $rangeWrapper = $crawler->filter('[data-testid="datepicker-range"]');
        self::assertGreaterThan(0, $rangeWrapper->count());

        $input = $rangeWrapper->filter('input[data-controller="tailsfadmin--datepicker"]');
        self::assertCount(1, $input, 'L\'input range doit avoir data-controller="tailsfadmin--datepicker"');

        $mode = $input->attr('data-tailsfadmin--datepicker-mode-value');
        self::assertSame('range', $mode, 'Le mode doit être "range" pour le datepicker plage');
    }

    public function testDatepickerWithTimeSetsEnableTimeTrue(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $timeWrapper = $crawler->filter('[data-testid="datepicker-time"]');
        self::assertGreaterThan(0, $timeWrapper->count());

        $input = $timeWrapper->filter('input[data-controller="tailsfadmin--datepicker"]');
        self::assertCount(1, $input);

        $enableTime = $input->attr('data-tailsfadmin--datepicker-enable-time-value');
        self::assertSame('true', $enableTime, 'enable-time-value doit être "true" pour le datepicker avec heure');
    }

    public function testDatepickerDisabledRendersDisabledAttribute(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $disabledWrapper = $crawler->filter('[data-testid="datepicker-disabled"]');
        self::assertGreaterThan(0, $disabledWrapper->count());

        $input = $disabledWrapper->filter('input[disabled]');
        self::assertCount(1, $input, 'L\'input désactivé doit avoir l\'attribut disabled');
    }

    public function testDatepickerInputHasCalendarIcon(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $singleWrapper = $crawler->filter('[data-testid="datepicker-single"]');
        self::assertGreaterThan(0, $singleWrapper->count());

        // L'icône calendrier est dans un div avec pointer-events-none
        $iconWrapper = $singleWrapper->filter('.pointer-events-none');
        self::assertGreaterThan(0, $iconWrapper->count(), 'L\'icône calendrier (pointer-events-none) doit être présente');
    }

    public function testDatepickerHasDatepickerSectionInNav(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $navLink = $crawler->filter('nav[aria-label] a[href="#section-datepicker"]');
        self::assertGreaterThan(0, $navLink->count(), 'Le sommaire de la galerie doit contenir un lien vers #section-datepicker');
    }
}
