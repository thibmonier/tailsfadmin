<?php

declare(strict_types=1);

namespace App\Tests;

use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Smoke test d'intégration (US-030), exécuté DANS l'app Symfony vierge générée.
 *
 * La CI EST le test : ce fichier prouve, hors du monorepo de la démo, que le
 * bundle installé comme un tiers (archive dist) rend une page admin fonctionnelle
 * et que le garde-fou de vendoring (US-027) se déclenche quand une lib manque.
 *
 * Deux groupes, joués en deux phases par le job CI :
 *   - `failure` AVANT `tailsfadmin:assets:install` (T-030-04),
 *   - `nominal` APRÈS (T-030-03).
 */
final class SmokeTest extends PantherTestCase
{
    #[Group('nominal')]
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

        // Aucune lib manquante signalée par le préflight (marqueur DOM US-027).
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

    #[Group('failure')]
    public function testMissingVendoringIsReported(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/chart');

        // Sans `tailsfadmin:assets:install`, ApexCharts n'est pas dans l'importmap.
        // Le préflight marque le <html> avec la liste des libs manquantes.
        $client->waitFor('html[data-tailsfadmin-missing-libs]', 5);
        $missing = (string) $client->getWebDriver()
            ->findElement(WebDriverBy::cssSelector('html'))
            ->getAttribute('data-tailsfadmin-missing-libs');

        self::assertStringContainsString(
            'apexcharts',
            $missing,
            'Le garde-fou de vendoring (US-027) n\'a pas signalé ApexCharts manquant.'
        );
    }
}
