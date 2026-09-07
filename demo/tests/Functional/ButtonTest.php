<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-010 (composant tsf:Ui:Button).
 *
 * Couverture : rendu DOM du composant Button (6 variantes, href → <a>,
 * loading + aria-busy, disabled).
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant implémentation du composant Button
 *   GREEN: passent après création de Button.php + Button.html.twig
 */
final class ButtonTest extends WebTestCase
{
    // ─── Variante primary ────────────────────────────────────────────────────

    public function testPrimaryButtonHasCorrectClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'button.bg-brand-500.text-white',
            'Le bouton primary doit avoir bg-brand-500 et text-white'
        );
    }

    // ─── Variante secondary ──────────────────────────────────────────────────

    public function testSecondaryButtonHasRingClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'button.ring-1.text-gray-700',
            'Le bouton secondary doit avoir ring-1 et text-gray-700'
        );
    }

    // ─── Variante danger ─────────────────────────────────────────────────────

    public function testDangerButtonHasErrorClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'button.bg-error-500',
            'Le bouton danger doit avoir bg-error-500'
        );
    }

    // ─── Variante success ────────────────────────────────────────────────────

    public function testSuccessButtonHasSuccessClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'button.bg-success-500',
            'Le bouton success doit avoir bg-success-500'
        );
    }

    // ─── Rendu <a> quand href est fourni ────────────────────────────────────

    public function testButtonWithHrefRendersAnchor(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'a.bg-brand-500',
            'Un Button avec href doit rendre une balise <a> (non un <button>)'
        );
    }

    // ─── État loading ────────────────────────────────────────────────────────

    public function testLoadingButtonHasAriaBusy(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[aria-busy="true"]',
            'Un bouton en état loading doit avoir aria-busy="true"'
        );
    }

    public function testLoadingButtonHasSpinner(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.animate-spin',
            'Un bouton en état loading doit afficher un spinner (.animate-spin)'
        );
    }

    // ─── État disabled ───────────────────────────────────────────────────────

    public function testDisabledButtonHasDisabledAttribute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'button[disabled]',
            'Un bouton disabled doit avoir l\'attribut disabled'
        );
    }

    // ─── Structure commune ───────────────────────────────────────────────────

    public function testButtonHasRoundedLgClass(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.rounded-lg.inline-flex',
            'Le bouton doit avoir les classes de base rounded-lg et inline-flex'
        );
    }
}
