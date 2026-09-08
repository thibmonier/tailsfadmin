<?php

declare(strict_types=1);

namespace App\Tests;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Smoke test d'intégration NOMINAL (US-030 / T-030-03), exécuté DANS l'app
 * Symfony vierge générée, APRÈS `tailsfadmin:assets:install`.
 *
 * La CI EST le test : ce fichier prouve, hors du monorepo de la démo, que le
 * bundle installé comme un tiers (archive dist) rend une page admin fonctionnelle
 * et qu'un composant JS monte réellement au navigateur.
 *
 * Le scénario d'échec (vendoring absent) est vérifié de façon déterministe au
 * niveau de l'importmap par le job CI (grep), pas au navigateur : les contrôleurs
 * du bundle étant `eager`, une lib manquante casse le lot de contrôleurs — un
 * garde-fou navigateur n'est donc pas fiable à tester. Le garde-fou console/DOM
 * du préflight reste livré pour l'expérience développeur.
 */
final class SmokeTest extends PantherTestCase
{
    public function testAdminLayoutRendersAndDropdownMounts(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/');

        // Layout admin rendu (une exception/500 ferait échouer ces sélecteurs).
        self::assertSelectorExists('aside', 'La sidebar du layout admin est absente.');
        self::assertSelectorExists('header', 'Le header du layout admin est absent.');
        self::assertSelectorTextContains('[data-testid="smoke-ok"]', 'hors monorepo');

        // Thème CSS (US-028) appliqué : la police Outfit est effective sur le body.
        $fontFamily = (string) $client->executeScript(
            'return window.getComputedStyle(document.body).fontFamily;'
        );
        self::assertStringContainsStringIgnoringCase('outfit', $fontFamily);

        // Aucune lib manquante signalée par le préflight (marqueur DOM US-027) :
        // le vendoring a bien eu lieu avant ce test.
        self::assertSelectorNotExists(
            'html[data-tailsfadmin-missing-libs]',
            'Le préflight signale des libs manquantes alors que le vendoring a eu lieu.'
        );

        // Montage JS réel : le Dropdown ouvre son menu au clic.
        $client->waitForVisibility('[data-testid="dd-trigger"]', 5);
        $client->getWebDriver()
            ->findElement(WebDriverBy::cssSelector('[data-testid="dd-trigger"]'))
            ->click();
        $client->waitForVisibility('[data-testid="dd-item"]', 5);
        self::assertSelectorIsVisible('[data-testid="dd-item"]');
    }
}
