<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel — US-024 : i18n + RTL.
 *
 * Vérifie la bascule de langue (session), le repli sur la locale par défaut
 * pour une locale hors whitelist, et l'attribut dir="rtl" pour l'arabe.
 */
final class LocaleTest extends WebTestCase
{
    public function testSwitchToEnglishTranslatesChrome(): void
    {
        $client = static::createClient();

        $client->request('GET', '/locale/en');
        self::assertResponseRedirects();

        $client->request('GET', '/');
        // Le menu passe en anglais (fr « Tableau de bord » → en « Dashboard »).
        self::assertSelectorTextContains('body', 'Dashboard');
        self::assertSelectorTextNotContains('body', 'Tableau de bord');
    }

    public function testSwitchToFrenchTranslatesChrome(): void
    {
        $client = static::createClient();

        $client->request('GET', '/locale/en');
        $client->request('GET', '/locale/fr');
        $client->request('GET', '/');

        self::assertSelectorTextContains('body', 'Tableau de bord');
    }

    public function testUnknownLocaleFallsBackToDefault(): void
    {
        $client = static::createClient();

        // Locale hors whitelist : ignorée, on reste sur le défaut (fr).
        $client->request('GET', '/locale/zz');
        self::assertResponseRedirects();

        $client->request('GET', '/');
        self::assertSelectorTextContains('body', 'Tableau de bord');
    }

    public function testArabicSetsRtlDirection(): void
    {
        $client = static::createClient();

        $client->request('GET', '/locale/ar');
        $crawler = $client->request('GET', '/');

        self::assertSame('rtl', $crawler->filter('html')->attr('dir'));
        self::assertSame('ar', $crawler->filter('html')->attr('lang'));
    }

    public function testDefaultDirectionIsLtr(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertSame('ltr', $crawler->filter('html')->attr('dir'));
    }
}
