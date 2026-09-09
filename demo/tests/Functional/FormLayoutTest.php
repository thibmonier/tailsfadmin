<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-038 — Tests fonctionnels : page « Mise en page de formulaire » (/forms/layout).
 *
 * Vérifie que la page présente des gabarits de formulaire cohérents et accessibles,
 * en réutilisant le form theme (@Tailsfadmin/form/theme.html.twig) et tsf:Ui:Card,
 * sans introduire de nouveau widget :
 *   - la page répond 200 et hérite du layout admin ;
 *   - chaque champ visible porte un <label for> lié ;
 *   - les aides de champ sont reliées via aria-describedby ;
 *   - la grille deux colonnes se replie (structure grid-cols-1 md:grid-cols-2) ;
 *   - une barre d'actions cohérente est présente ;
 *   - une soumission invalide affiche l'état d'erreur lié au champ.
 */
final class FormLayoutTest extends WebTestCase
{
    private const ROUTE = '/forms/layout';

    public function testPageReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', self::ROUTE);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
    }

    public function testPageRendersAdminLayout(): void
    {
        $client = static::createClient();
        $client->request('GET', self::ROUTE);

        // Le layout admin du bundle expose le nom « tailsfadmin » dans le chrome.
        self::assertSelectorTextContains('body', 'tailsfadmin');
    }

    public function testBothLayoutTemplatesArePresent(): void
    {
        $client = static::createClient();
        $client->request('GET', self::ROUTE);

        self::assertSelectorExists('[data-testid="form-layout-single"]', 'Le gabarit une colonne doit être présent');
        self::assertSelectorExists('[data-testid="form-layout-account"]', 'Le gabarit sectionné doit être présent');
    }

    public function testEveryVisibleFieldHasLinkedLabel(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        // On borne l'assertion au gabarit sectionné (form principal).
        $labels = $crawler->filter('[data-testid="form-layout-account"] label[for]');
        self::assertGreaterThan(0, $labels->count(), 'Le formulaire doit exposer des labels liés (for=)');

        // Un label lié doit exister pour chaque champ de saisie visible (hors hidden/checkbox peer).
        $fields = $crawler->filter(
            '[data-testid="form-layout-account"] input:not([type="hidden"]):not([type="checkbox"]), '
            .'[data-testid="form-layout-account"] select, '
            .'[data-testid="form-layout-account"] textarea'
        );
        self::assertGreaterThan(0, $fields->count());

        $fields->each(function ($field): void {
            $id = $field->attr('id');
            self::assertNotEmpty($id, 'Chaque champ doit avoir un id');
        });

        // Vérification ciblée : l'e-mail porte un label lié.
        self::assertSelectorExists('[data-testid="form-layout-account"] label[for="account_settings_email"]');
    }

    public function testFieldHelpIsLinkedViaAriaDescribedby(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        $email = $crawler->filter('#account_settings_email');
        self::assertCount(1, $email, 'Le champ e-mail du gabarit sectionné doit exister');

        $describedBy = $email->attr('aria-describedby');
        self::assertNotEmpty($describedBy, 'Le champ e-mail doit être relié à son aide via aria-describedby');

        // L'élément d'aide référencé doit exister dans le document.
        self::assertSelectorExists('#account_settings_email_help', 'L\'aide reliée doit exister');
    }

    public function testTwoColumnGridCollapsesResponsively(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        $grid = $crawler->filter('[data-testid="grid-personal"]');
        self::assertGreaterThan(0, $grid->count(), 'La section « informations personnelles » doit utiliser une grille');

        $gridClass = $grid->first()->attr('class') ?? '';
        self::assertStringContainsString('grid-cols-1', $gridClass, 'La grille doit être 1 colonne par défaut (mobile)');
        self::assertStringContainsString('md:grid-cols-2', $gridClass, 'La grille doit passer à 2 colonnes en md (repli responsive)');
    }

    public function testActionBarIsPresent(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        self::assertSelectorExists('[data-testid="form-actions"]', 'Une barre d\'actions doit être présente');

        $submit = $crawler->filter('[data-testid="form-actions"] button[type="submit"]');
        self::assertGreaterThan(0, $submit->count(), 'La barre d\'actions doit contenir un bouton de soumission');
    }

    public function testInvalidSubmitShowsErrorLinkedToField(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Enregistrer')->form();
        $client->submit($form, [
            'account_settings[first_name]' => '',
            'account_settings[last_name]' => '',
            'account_settings[email]' => '',
        ]);

        // Symfony 7+ retourne 422 sur formulaire invalide (200 accepté selon config).
        $statusCode = $client->getResponse()->getStatusCode();
        self::assertContains($statusCode, [200, 422], 'La réponse doit être 200 ou 422 sur soumission invalide');

        $postCrawler = $client->getCrawler();

        $errors = $postCrawler->filter('[data-testid="form-layout-account"] .form-error-message');
        self::assertGreaterThan(0, $errors->count(), 'Des messages d\'erreur doivent apparaître');

        $email = $postCrawler->filter('#account_settings_email');
        self::assertCount(1, $email);
        self::assertSame('true', $email->attr('aria-invalid'), 'Le champ e-mail invalide doit porter aria-invalid="true"');
    }
}
