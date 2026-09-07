<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-005 (bascule dark mode persistante, Stimulus).
 *
 * Couverture : câblage DOM côté serveur (présence des attributs Stimulus,
 * script anti-FOUC). Le comportement JS pur (toggle/persist/localStorage bloqué)
 * requiert un navigateur réel et ne peut pas être testé ici.
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant implémentation
 *   GREEN: passent après implémentation du contrôleur theme et du bouton toggle
 */
final class DarkModeTest extends WebTestCase
{
    public function testThemeControllerDataAttributePresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller~="tailsfadmin--theme"]',
            'Le data-controller "tailsfadmin--theme" doit être présent dans le HTML rendu'
        );
    }

    public function testThemeToggleButtonHasDataAction(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $toggleButtons = $crawler->filter('[data-action*="tailsfadmin--theme#toggle"]');
        self::assertGreaterThan(
            0,
            $toggleButtons->count(),
            'Un bouton avec data-action contenant "tailsfadmin--theme#toggle" doit exister'
        );
    }

    public function testThemeToggleButtonHasAriaLabel(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        $toggleButton = $crawler->filter('[data-action*="tailsfadmin--theme#toggle"]')->first();
        $ariaLabel = $toggleButton->attr('aria-label');

        self::assertNotEmpty(
            $ariaLabel,
            'Le bouton toggle doit avoir un aria-label non vide pour l\'accessibilité'
        );
    }

    public function testAntiFoucScriptPresentInHead(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        // Le script anti-FOUC doit être dans le <head>
        $headScripts = $crawler->filter('head script');
        self::assertGreaterThan(
            0,
            $headScripts->count(),
            'Un script doit être présent dans le <head> (anti-FOUC dark mode)'
        );

        // Le script doit contenir la logique dark mode (localStorage + prefers-color-scheme)
        $found = false;
        foreach ($headScripts as $script) {
            $content = $script->textContent;
            if (str_contains($content, 'localStorage') && str_contains($content, 'dark')) {
                $found = true;
                // Vérifier la taille < 200 octets
                self::assertLessThan(
                    200,
                    strlen($content),
                    'Le script anti-FOUC doit être < 200 octets (compact, bloquant)'
                );
                break;
            }
        }

        self::assertTrue($found, 'Le script anti-FOUC (localStorage + dark) doit être dans le <head>');
    }

    public function testThemeToggleButtonContainsSunAndMoonSvg(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        // Le bouton toggle doit contenir des SVG (icônes soleil et lune)
        $toggleButton = $crawler->filter('[data-action*="tailsfadmin--theme#toggle"]')->first();
        $svgs = $toggleButton->filter('svg');

        self::assertGreaterThanOrEqual(
            2,
            $svgs->count(),
            'Le bouton toggle doit contenir au moins 2 SVG (icônes soleil et lune)'
        );
    }
}
