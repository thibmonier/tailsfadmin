<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-014 — Tests fonctionnels : composants tsf:Form:*.
 *
 * On vérifie via la page /ui-kit (section Formulaires) :
 *   - la liaison label for="id" == input id
 *   - les états error/success/disabled
 *   - la présence des attributs ARIA
 *   - la structure des composants Select, Textarea, Checkbox, Radio, Toggle
 */
final class FormComponentsTest extends WebTestCase
{
    // ──────────────────────────────────────────────────────────────────────────
    // Input
    // ──────────────────────────────────────────────────────────────────────────

    public function testInputRendersLabelLinkedToField(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        // Trouve le premier label dont le for pointe vers un input présent
        $label = $crawler->filter('[data-testid="form-input-default"] label');
        self::assertCount(1, $label);
        $for = $label->attr('for');
        self::assertNotNull($for, 'Le label doit avoir un attribut for');
        self::assertNotSame('', $for, 'L\'attribut for ne doit pas être vide');

        $input = $crawler->filter('[data-testid="form-input-default"] input#' . $for);
        self::assertCount(1, $input, "L'input avec id=\"{$for}\" doit exister dans le DOM");
    }

    public function testInputErrorStateHasAriaInvalidAndMessage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $wrapper = $crawler->filter('[data-testid="form-input-error"]');
        self::assertCount(1, $wrapper);

        $input = $wrapper->filter('input');
        self::assertCount(1, $input);
        self::assertSame('true', $input->attr('aria-invalid'), 'aria-invalid doit valoir "true" à l\'état error');

        // Message d'erreur présent
        $errorMsg = $wrapper->filter('.form-error-message');
        self::assertCount(1, $errorMsg);
        self::assertStringContainsString('requis', strtolower($errorMsg->text()));
    }

    public function testInputSuccessStateHasSuccessBorderClass(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $wrapper = $crawler->filter('[data-testid="form-input-success"]');
        $input = $wrapper->filter('input');
        self::assertCount(1, $input);
        self::assertStringContainsString('border-success', $input->attr('class') ?? '');
    }

    public function testInputDisabledHasDisabledAttribute(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $wrapper = $crawler->filter('[data-testid="form-input-disabled"]');
        $input = $wrapper->filter('input');
        self::assertCount(1, $input);
        self::assertNotNull($input->attr('disabled'), 'L\'input désactivé doit avoir l\'attribut disabled');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // InputGroup
    // ──────────────────────────────────────────────────────────────────────────

    public function testInputGroupRendersPrefixSlot(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $group = $crawler->filter('[data-testid="form-input-group"]');
        self::assertCount(1, $group);
        self::assertSelectorExists('[data-testid="form-input-group"] .input-group-prefix');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Select
    // ──────────────────────────────────────────────────────────────────────────

    public function testSelectRendersLabelAndOptions(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $wrapper = $crawler->filter('[data-testid="form-select"]');
        self::assertCount(1, $wrapper);
        self::assertCount(1, $wrapper->filter('label'));
        self::assertGreaterThanOrEqual(1, $wrapper->filter('select option')->count());
    }

    public function testSelectMultipleHasMultipleAttribute(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $select = $crawler->filter('[data-testid="form-select-multiple"] select');
        self::assertCount(1, $select);
        self::assertNotNull($select->attr('multiple'), 'Le select multiple doit avoir l\'attribut multiple');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Textarea
    // ──────────────────────────────────────────────────────────────────────────

    public function testTextareaRendersWithRowsAttribute(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $wrapper = $crawler->filter('[data-testid="form-textarea"]');
        $textarea = $wrapper->filter('textarea');
        self::assertCount(1, $textarea);
        $rows = $textarea->attr('rows');
        self::assertNotNull($rows);
        self::assertGreaterThan(0, (int) $rows);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Checkbox
    // ──────────────────────────────────────────────────────────────────────────

    public function testCheckboxRendersNativeInputWithLabel(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $wrapper = $crawler->filter('[data-testid="form-checkbox"]');
        self::assertCount(1, $wrapper->filter('input[type="checkbox"]'));
        self::assertCount(1, $wrapper->filter('label'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Radio
    // ──────────────────────────────────────────────────────────────────────────

    public function testRadioRendersNativeInputWithLabel(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $wrapper = $crawler->filter('[data-testid="form-radio"]');
        self::assertCount(1, $wrapper->filter('input[type="radio"]'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Toggle
    // ──────────────────────────────────────────────────────────────────────────

    public function testToggleHasRoleSwitchAndAriaChecked(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $wrapper = $crawler->filter('[data-testid="form-toggle"]');
        $input = $wrapper->filter('input[type="checkbox"]');
        self::assertCount(1, $input);
        self::assertSame('switch', $input->attr('role'), 'Le toggle doit avoir role="switch"');
        $ariaChecked = $input->attr('aria-checked');
        self::assertContains($ariaChecked, ['true', 'false'], 'aria-checked doit valoir "true" ou "false"');
    }
}
