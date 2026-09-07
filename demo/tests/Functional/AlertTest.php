<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-008 (composant tsf:Ui:Alert).
 *
 * Couverture : rendu DOM du composant Alert (4 variantes, role=alert,
 * contrôleur Stimulus si dismissible).
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant implémentation du composant Alert
 *   GREEN: passent après création de Alert.php + Alert.html.twig
 *
 * Ces tests utilisent la page /ui-kit (T-TECH-03) ou une route dédiée.
 * En attendant, on teste directement via le composant rendu sur la home.
 */
final class AlertTest extends WebTestCase
{
    // ─── Structure de base ──────────────────────────────────────────────────

    public function testAlertHasRoleAlert(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[role="alert"]',
            'Le composant Alert doit avoir role="alert"'
        );
    }

    // ─── Variante success ───────────────────────────────────────────────────

    public function testSuccessAlertHasSuccessClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-success-50[role="alert"]',
            'L\'alerte success doit avoir bg-success-50'
        );
    }

    // ─── Variante error ─────────────────────────────────────────────────────

    public function testErrorAlertHasErrorClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-error-50[role="alert"]',
            'L\'alerte error doit avoir bg-error-50'
        );
    }

    // ─── Variante warning ───────────────────────────────────────────────────

    public function testWarningAlertHasWarningClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-warning-50[role="alert"]',
            'L\'alerte warning doit avoir bg-warning-50'
        );
    }

    // ─── Variante info ──────────────────────────────────────────────────────

    public function testInfoAlertHasInfoClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-blue-light-50[role="alert"]',
            'L\'alerte info doit avoir bg-blue-light-50'
        );
    }

    // ─── Mode dismissible ───────────────────────────────────────────────────

    public function testDismissibleAlertHasStimulusController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller~="tailsfadmin--alert-dismiss"]',
            'Une alerte dismissible doit avoir le contrôleur tailsfadmin--alert-dismiss'
        );
    }

    public function testDismissibleAlertHasDismissButton(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-action*="tailsfadmin--alert-dismiss#dismiss"]',
            'Le bouton de fermeture doit avoir le data-action dismiss'
        );
    }
}
