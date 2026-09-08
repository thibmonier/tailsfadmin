<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Facebook\WebDriver\WebDriverKeys;
use Symfony\Component\Panther\PantherTestCase;

/**
 * T-TECH-02 — Spike Panther : 1er test navigateur réel.
 *
 * Preuve de comportement JS runtime (impossible à capturer avec WebTestCase) :
 *   1. Modal : clic -> panel visible -> fermeture Échap -> panel masqué
 *   2. Theme toggle : clic -> classe .dark appliquée sur <html>
 *
 * Configuration :
 *   - Navigateur : Chromium headless (PANTHER_CHROME_BINARY dans phpunit.dist.xml)
 *   - Driver     : ChromeDriver local (PANTHER_CHROME_DRIVER_BINARY = drivers/chromedriver)
 *   - Serveur    : PHP built-in démarré automatiquement par Panther/ServerExtension
 *
 * Isolation :
 *   - Suite PHPUnit "e2e" (exclue de la suite par défaut "Project Test Suite")
 *   - Lancer avec : cd demo && composer test:e2e
 *                ou vendor/bin/phpunit --testsuite e2e
 *   - composer test (suite rapide) ne lance PAS ces tests
 *
 * Prérequis :
 *   - Chromium installé (ex. /Applications/Chromium.app sur macOS)
 *   - ChromeDriver présent dans drivers/chromedriver (vendor/bin/bdi detect drivers)
 */
final class ModalE2ETest extends PantherTestCase
{
    /**
     * Prouve que le contrôleur Stimulus "tailsfadmin--modal" fonctionne réellement :
     *   - Le panel est hidden au départ (initial DOM state)
     *   - Après clic sur le déclencheur, le panel est visible (JS a retiré l'attribut hidden)
     *   - Après pression de la touche Échap, le panel est caché (focus + keyboard handler)
     *
     * Note : la page /ui-kit contient plusieurs modales ; on vérifie qu'AU MOINS UNE
     * est ouverte, et que la première se referme bien via Échap.
     */
    public function testModalOpenAndCloseViaEscape(): void
    {
        $client = static::createPantherClient([
            'browser' => static::CHROME,
        ]);

        $crawler = $client->request('GET', '/ui-kit');

        // Au chargement, tous les panels dialog doivent avoir l'attribut hidden
        self::assertSelectorExists(
            '[data-tailsfadmin--modal-target="panel"][hidden]',
            'Au chargement, le panel modal doit avoir l\'attribut hidden',
        );

        // Cliquer le déclencheur de la PREMIÈRE modale (wrapper data-action)
        $client->executeScript("
            document.querySelector('[data-action*=\"tailsfadmin--modal#open\"]').click();
        ");

        // Attendre qu'au moins un dialog soit visible (JS a retiré l'attribut hidden)
        $client->waitFor('[role="dialog"]:not([hidden])');

        // Au moins une modale est maintenant visible
        self::assertSelectorExists(
            '[role="dialog"]:not([hidden])',
            'Après clic sur le déclencheur, au moins un panel doit être visible (sans attribut hidden)',
        );

        // Focus trap : le focus doit avoir été déplacé DANS le dialog ouvert.
        // Garde de régression : un accès invalide au sélecteur focusable faisait
        // échouer #trapFocus (le focus restait sur le déclencheur, hors panel).
        $focusInside = $client->executeScript(
            'return document.querySelector(\'[role="dialog"]:not([hidden])\').contains(document.activeElement);'
        );
        self::assertTrue(
            $focusInside,
            'Après ouverture, le focus doit être piégé dans le dialog (focus trap fonctionnel)',
        );

        // Fermer via la touche Échap (WebDriverKeys::ESCAPE = \xEE\x80\x8C)
        $client->getKeyboard()->pressKey(WebDriverKeys::ESCAPE);

        // Attendre que TOUS les panels soient à nouveau cachés
        $client->waitFor('[data-tailsfadmin--modal-target="panel"][hidden]');

        self::assertSelectorNotExists(
            '[role="dialog"]:not([hidden])',
            'Après la touche Échap, aucun panel ne doit être visible',
        );
    }

    /**
     * Prouve que le contrôleur Stimulus "tailsfadmin--theme" (US-005) fonctionne :
     *   - Clic sur le toggle -> la classe .dark est ajoutée sur <html>
     *   - Second clic -> la classe .dark est retirée
     */
    public function testThemeToggleAddsDarkClassOnHtml(): void
    {
        $client = static::createPantherClient([
            'browser' => static::CHROME,
        ]);

        $client->request('GET', '/');

        // S'assurer que .dark n'est pas présent initialement
        $client->executeScript("document.documentElement.classList.remove('dark');");

        // Cliquer le bouton toggle thème
        $client->executeScript("
            document.querySelector('[data-controller~=\"tailsfadmin--theme\"]').click();
        ");

        // Attendre que la classe .dark soit présente sur <html>
        $client->waitForAttributeToContain('html', 'class', 'dark');

        self::assertSelectorAttributeContains(
            'html',
            'class',
            'dark',
            'Après un clic sur le toggle, la classe .dark doit être présente sur <html>',
        );

        // Second clic : retrait de .dark
        $client->executeScript("
            document.querySelector('[data-controller~=\"tailsfadmin--theme\"]').click();
        ");

        // Attendre que la classe .dark disparaisse (méthode correcte : waitForAttributeToNotContain)
        $client->waitForAttributeToNotContain('html', 'class', 'dark');

        self::assertSelectorAttributeNotContains(
            'html',
            'class',
            'dark',
            'Après le second clic sur le toggle, la classe .dark doit être retirée',
        );
    }
}
