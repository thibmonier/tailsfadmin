<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

/**
 * E2E — US-039 : montage réel des interactions de la page /integrations.
 *
 * Prouve au navigateur que :
 *   1. le contrôleur tailsfadmin--clipboard copie et donne un retour visuel ;
 *   2. le bouton « Révéler » (CSS peer) affiche la clé en clair ;
 *   3. la génération via modal affiche la nouvelle clé une seule fois.
 *
 * Les assertions portent sur des effets DOM observables (recommandé pour le
 * presse-papiers, fragile en headless) plutôt que sur le contenu du presse-papiers.
 */
final class IntegrationsE2ETest extends PantherTestCase
{
    public function testRevealAndCopyGiveVisualFeedback(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/integrations');

        $rowPlain = '[data-key-id="key_prod"] .api-key-plain';
        $copyButton = '[data-key-id="key_prod"] [data-controller="tailsfadmin--clipboard"]';

        // 1. Révéler : la clé en clair est masquée au départ, visible après clic.
        $displayBefore = $client->executeScript(
            "return getComputedStyle(document.querySelector('{$rowPlain}')).display;"
        );
        self::assertSame('none', $displayBefore, 'La clé en clair doit être masquée par défaut');

        $client->executeScript(
            "document.querySelector('[data-key-id=\"key_prod\"] [data-testid=\"reveal-toggle\"]').click();"
        );
        $client->waitForVisibility($rowPlain);

        $displayAfter = $client->executeScript(
            "return getComputedStyle(document.querySelector('{$rowPlain}')).display;"
        );
        self::assertNotSame('none', $displayAfter, 'Après « Révéler », la clé en clair doit être visible');

        // 2. Copier : le contrôleur pose data-copied et change le libellé du bouton.
        $client->executeScript("document.querySelector('{$copyButton}').click();");
        $client->waitFor("{$copyButton}[data-copied]");

        $label = $client->executeScript(
            "return document.querySelector('{$copyButton} [data-tailsfadmin--clipboard-target=\"label\"]').textContent;"
        );
        self::assertNotSame('Copier', $label, 'Le bouton de copie doit afficher un retour visuel');
    }

    public function testGenerateKeyThroughModal(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/integrations');

        // Ouvre le modal de génération.
        $client->executeScript(
            "document.querySelector('[data-testid=\"generate-trigger\"]').click();"
        );
        $client->waitFor('[role="dialog"]:not([hidden])');

        // Renseigne le nom et soumet.
        $client->executeScript("document.querySelector('#api_key_name').value = 'Clé E2E';");
        $client->executeScript(
            "document.querySelector('[data-testid=\"generate-form\"] button[type=\"submit\"]').click();"
        );

        // Après le Post/Redirect/Get, la clé générée est affichée une fois.
        $client->waitFor('[data-testid="generated-key"]');
        self::assertSelectorExists('[data-testid="generated-key"]');
        self::assertSelectorTextContains('[data-testid="generated-key"]', 'sk_live_');
    }
}
