<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-039 — Tests fonctionnels : page « Integrations / API keys » (/integrations).
 *
 * Couvre le rendu HTTP (les interactions JS — copie presse-papiers, révéler —
 * sont couvertes par l'E2E Panther). Données factices en session :
 *   - liste des clés masquées + contrôles de copie/révélation ;
 *   - onglets (Tabs) clés / fournisseurs ;
 *   - génération d'une clé (affichée une seule fois) + validation ;
 *   - révocation qui bascule le badge de statut.
 */
final class IntegrationsTest extends WebTestCase
{
    private const ROUTE = '/integrations';

    public function testPageReturns200AndRendersAdminLayout(): void
    {
        $client = static::createClient();
        $client->request('GET', self::ROUTE);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'tailsfadmin');
    }

    public function testTabsAndPanelsArePresent(): void
    {
        $client = static::createClient();
        $client->request('GET', self::ROUTE);

        self::assertSelectorExists('[role="tablist"]', 'La page doit utiliser des onglets (Tabs)');
        self::assertSelectorExists('[data-testid="tab-panel-keys"]');
        self::assertSelectorExists('[data-testid="tab-panel-providers"]');
    }

    public function testSeededKeysAreRenderedMaskedWithControls(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        $rows = $crawler->filter('[data-testid="api-key-row"]');
        self::assertGreaterThanOrEqual(3, $rows->count(), 'Les clés factices seedées doivent être listées');

        // Clés masquées.
        self::assertStringContainsString('••••', $client->getResponse()->getContent() ?: '');
        self::assertGreaterThan(0, $crawler->filter('.api-key-masked')->count());
        // Valeur en clair présente dans le DOM pour révélation/copie.
        self::assertGreaterThan(0, $crawler->filter('.api-key-plain')->count());

        // Contrôle de révélation (CSS peer) + bouton de copie (contrôleur Stimulus).
        self::assertGreaterThanOrEqual(3, $crawler->filter('[data-testid="reveal-toggle"]')->count());
        self::assertGreaterThanOrEqual(3, $crawler->filter('[data-controller="tailsfadmin--clipboard"]')->count());
    }

    public function testGenerateValidKeyShowsItOnceAndAddsRow(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', self::ROUTE);

        $form = $crawler->filter('[data-testid="generate-form"]')->form();
        $crawler = $client->submit($form, [
            'api_key[name]' => 'Clé de test',
            'api_key[scope]' => 'read',
        ]);

        self::assertResponseIsSuccessful();

        // La clé en clair est affichée une seule fois.
        $banner = $crawler->filter('[data-testid="generated-key"]');
        self::assertCount(1, $banner, 'La clé générée doit être affichée une fois');
        self::assertStringContainsString('sk_live_', $banner->text());

        // La nouvelle clé apparaît dans la liste (4 lignes désormais).
        self::assertCount(4, $crawler->filter('[data-testid="api-key-row"]'));
        self::assertSelectorTextContains('[data-testid="tab-panel-keys"]', 'Clé de test');
    }

    public function testGeneratedKeyIsShownOnlyOnce(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', self::ROUTE);

        $form = $crawler->filter('[data-testid="generate-form"]')->form();
        $client->submit($form, ['api_key[name]' => 'Éphémère', 'api_key[scope]' => 'read']);
        self::assertSelectorExists('[data-testid="generated-key"]');

        // Un nouveau chargement ne doit plus révéler la clé (flash consommé).
        $client->request('GET', self::ROUTE);
        self::assertSelectorNotExists('[data-testid="generated-key"]');
    }

    public function testGenerateInvalidShowsError(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        $form = $crawler->filter('[data-testid="generate-form"]')->form();
        $client->submit($form, ['api_key[name]' => '', 'api_key[scope]' => 'read']);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorExists('[data-testid="generate-error"]');
        self::assertSelectorExists('.form-error-message');
    }

    public function testRevokeChangesStatusBadge(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', self::ROUTE);

        // La clé « Production » (key_prod) est active au départ.
        $prodRow = $crawler->filter('[data-key-id="key_prod"]');
        self::assertCount(1, $prodRow);
        self::assertStringContainsString('Active', $prodRow->filter('[data-testid="api-key-status"]')->text());

        // Révocation via le formulaire dédié de cette ligne.
        $form = $crawler->filter('[data-testid="revoke-form-key_prod"]')->form();
        $crawler = $client->submit($form);

        self::assertResponseIsSuccessful();
        $prodRow = $crawler->filter('[data-key-id="key_prod"]');
        self::assertStringContainsString('Révoquée', $prodRow->filter('[data-testid="api-key-status"]')->text());
        self::assertSelectorExists('[data-testid="flash-success"]');
    }
}
