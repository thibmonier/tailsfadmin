<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-014 — Tests fonctionnels : Form Theme Symfony (@Tailsfadmin/form/theme.html.twig).
 *
 * Vérifie via /ui-kit que les blocks Symfony Forms sont surchargés
 * pour rendre en style TailAdmin :
 *   - le label porte les classes TailAdmin (mb-1.5, text-gray-700…)
 *   - l'input porte les classes TailAdmin (border-gray-300, rounded-lg…)
 *   - l'état error : classe error sur le champ + .form-error-message + aria-invalid
 *   - le textarea porte les classes TailAdmin
 *   - le select (ChoiceType collapsed) porte les classes TailAdmin
 */
final class FormThemeTest extends WebTestCase
{
    // ──────────────────────────────────────────────────────────────────────────
    // Rendu en état par défaut (GET)
    // ──────────────────────────────────────────────────────────────────────────

    public function testFormLabelHasTailAdminClasses(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        // Le label doit porter les classes TailAdmin définies dans form_label
        $label = $crawler->filter('[data-testid="form-theme-demo"] label[for]')->first();
        self::assertGreaterThan(0, $label->count(), 'Un label lié (for=) doit être présent dans le form theme demo');

        $labelClass = $label->attr('class') ?? '';
        self::assertStringContainsString('text-gray-700', $labelClass, 'Le label doit avoir la classe TailAdmin text-gray-700');
        self::assertStringContainsString('mb-1.5', $labelClass, 'Le label doit avoir la classe TailAdmin mb-1.5');
    }

    public function testFormInputHasTailAdminBorderClass(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        // L'input email doit porter les classes TailAdmin (border-gray-300, rounded-lg…)
        $input = $crawler->filter('[data-testid="form-theme-demo"] input[type="email"]');
        self::assertCount(1, $input);

        $inputClass = $input->attr('class') ?? '';
        self::assertStringContainsString('border-gray-300', $inputClass, 'L\'input doit avoir la classe TailAdmin border-gray-300 à l\'état par défaut');
        self::assertStringContainsString('rounded-lg', $inputClass, 'L\'input doit avoir la classe TailAdmin rounded-lg');
    }

    public function testFormTextareaHasTailAdminClasses(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $textarea = $crawler->filter('[data-testid="form-theme-demo"] textarea');
        self::assertCount(1, $textarea);

        $textareaClass = $textarea->attr('class') ?? '';
        self::assertStringContainsString('rounded-lg', $textareaClass, 'Le textarea doit avoir la classe TailAdmin rounded-lg');
        self::assertStringContainsString('border-gray-300', $textareaClass, 'Le textarea doit avoir border-gray-300 à l\'état par défaut');
    }

    public function testFormSelectHasTailAdminClasses(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $select = $crawler->filter('[data-testid="form-theme-demo"] select');
        self::assertGreaterThan(0, $select->count(), 'Un select doit être présent dans le form theme demo');

        $selectClass = $select->first()->attr('class') ?? '';
        self::assertStringContainsString('rounded-lg', $selectClass, 'Le select doit avoir la classe TailAdmin rounded-lg');
    }

    public function testFormCheckboxRendersWithPeerClass(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        // La checkbox est rendue avec l'approche CSS peer (sr-only sur l'input)
        $checkboxInput = $crawler->filter('[data-testid="form-theme-demo"] input[type="checkbox"]');
        self::assertCount(1, $checkboxInput);

        $checkboxClass = $checkboxInput->attr('class') ?? '';
        self::assertStringContainsString('sr-only', $checkboxClass, 'La checkbox du form theme doit avoir la classe sr-only');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Rendu en état error (POST avec données invalides)
    // ──────────────────────────────────────────────────────────────────────────

    public function testFormThemeShowsErrorMessageAndAriaInvalidOnInvalidSubmit(): void
    {
        $client = static::createClient();

        // 1. GET pour récupérer le formulaire (token CSRF si actif)
        $crawler = $client->request('GET', '/ui-kit');
        self::assertResponseIsSuccessful();

        // 2. Soumettre avec des données invalides (champs obligatoires vides)
        $form = $crawler->selectButton('Envoyer')->form();
        $client->submit($form, [
            'demo_contact[name]' => '',
            'demo_contact[email]' => '',
            'demo_contact[message]' => '',
        ]);

        // Symfony 7+ retourne 422 (Unprocessable Content) sur formulaire invalide
        // — comportement correct selon RFC 9110 pour les erreurs de validation.
        $statusCode = $client->getResponse()->getStatusCode();
        self::assertContains($statusCode, [200, 422], 'La réponse doit être 200 ou 422 (formulaire invalide).');

        $postCrawler = $client->getCrawler();

        // Le message d'erreur doit être présent
        $errorMessages = $postCrawler->filter('[data-testid="form-theme-demo"] .form-error-message');
        self::assertGreaterThan(0, $errorMessages->count(), 'Des messages .form-error-message doivent apparaître après une soumission invalide');

        // L'input email doit avoir aria-invalid="true"
        $inputEmail = $postCrawler->filter('[data-testid="form-theme-demo"] input[type="email"]');
        self::assertCount(1, $inputEmail);
        self::assertSame('true', $inputEmail->attr('aria-invalid'), 'L\'input email doit avoir aria-invalid="true" en état error');

        // Les classes error border doivent être appliquées sur l'input email
        $inputEmailClass = $inputEmail->attr('class') ?? '';
        self::assertStringContainsString('border-error', $inputEmailClass, 'L\'input email doit avoir une classe border-error-* en état error');
    }

    public function testFormThemeErrorMessageContainsText(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $form = $crawler->selectButton('Envoyer')->form();
        $client->submit($form, [
            'demo_contact[name]' => '',
            'demo_contact[email]' => '',
            'demo_contact[message]' => '',
        ]);

        $postCrawler = $client->getCrawler();
        $firstError = $postCrawler->filter('[data-testid="form-theme-demo"] .form-error-message')->first();

        self::assertGreaterThan(0, $firstError->count(), 'Un message .form-error-message doit être présent');
        self::assertNotEmpty(trim($firstError->text()), 'Le message d\'erreur ne doit pas être vide');
    }
}
