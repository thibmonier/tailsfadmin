<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-011 (composant tsf:Ui:Modal).
 *
 * Couverture : rendu DOM du composant Modal (role="dialog", aria-modal,
 * target overlay, déclencheur câblé au contrôleur Stimulus).
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant implémentation du composant Modal
 *   GREEN: passent après création de Modal.php + Modal.html.twig + modal_controller.js
 *
 * Le comportement clavier réel (focus trap, Échap, navigation Tab/Shift+Tab)
 * est couvert par le spike Panther T-TECH-02.
 */
final class ModalTest extends WebTestCase
{
    // ─── Contrôleur Stimulus ─────────────────────────────────────────────────

    public function testModalHasStimulusController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller~="tailsfadmin--modal"]',
            'Le composant Modal doit avoir le contrôleur "tailsfadmin--modal"'
        );
    }

    // ─── Attributs ARIA du dialog ────────────────────────────────────────────

    public function testModalPanelHasRoleDialog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[role="dialog"]',
            'Le panel de la modale doit avoir role="dialog"'
        );
    }

    public function testModalPanelHasAriaModal(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[role="dialog"][aria-modal="true"]',
            'Le panel de la modale doit avoir aria-modal="true"'
        );
    }

    public function testModalPanelHasAriaLabelledby(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[role="dialog"][aria-labelledby]',
            'Le panel de la modale doit avoir aria-labelledby (lié au titre)'
        );
    }

    // ─── Panel masqué par défaut ─────────────────────────────────────────────

    public function testModalPanelIsHiddenByDefault(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[role="dialog"][hidden]',
            'Le panel de la modale doit être masqué par défaut (attribut hidden)'
        );
    }

    // ─── Target Stimulus ─────────────────────────────────────────────────────

    public function testModalPanelHasStimulusTarget(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--modal-target="panel"]',
            'Le panel doit avoir data-tailsfadmin--modal-target="panel"'
        );
    }

    public function testModalOverlayHasStimulusTarget(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--modal-target="overlay"]',
            'L\'overlay doit avoir data-tailsfadmin--modal-target="overlay"'
        );
    }

    // ─── Déclencheur câblé ───────────────────────────────────────────────────

    public function testModalOpenerHasOpenAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-action*="tailsfadmin--modal#open"]',
            'Le déclencheur de la modale doit avoir un data-action pointant vers modal#open'
        );
    }

    // ─── Bouton de fermeture ─────────────────────────────────────────────────

    public function testModalCloseButtonHasCloseAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-action*="tailsfadmin--modal#close"]',
            'Le bouton de fermeture doit avoir un data-action pointant vers modal#close'
        );
    }

    // ─── Slots ───────────────────────────────────────────────────────────────

    public function testModalHasHeaderContent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        // Le slot header est rendu à l'intérieur du dialog
        self::assertSelectorExists(
            '[role="dialog"] h3',
            'Le slot header de la modale doit contenir un titre (h3)'
        );
    }
}
