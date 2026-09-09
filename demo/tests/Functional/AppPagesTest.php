<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-033 — Tests fonctionnels : pages type applicatives (Settings, Pricing,
 * Invoice, Chat, File manager, Inbox). Assemblage de composants existants.
 */
final class AppPagesTest extends WebTestCase
{
    /** @return iterable<string, array{string}> */
    public static function pages(): iterable
    {
        yield 'settings' => ['/app/settings'];
        yield 'pricing' => ['/app/pricing'];
        yield 'invoice' => ['/app/invoice'];
        yield 'chat' => ['/app/chat'];
        yield 'files' => ['/app/files'];
        yield 'inbox' => ['/app/inbox'];
    }

    /** @dataProvider pages */
    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function testPageReturns200(string $route): void
    {
        $client = static::createClient();
        $client->request('GET', $route);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'tailsfadmin');
    }

    public function testSettingsUsesTabs(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/app/settings');

        self::assertSelectorExists('[data-testid="settings-tabs"] [role="tablist"]');
        self::assertGreaterThan(0, $crawler->filter('[data-testid="settings-tabs"] [role="tab"]')->count());
    }

    public function testPricingHasThreePlans(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/app/pricing');

        self::assertCount(3, $crawler->filter('[data-testid="pricing-plan"]'));
    }

    public function testInvoiceShowsTotal(): void
    {
        $client = static::createClient();
        $client->request('GET', '/app/invoice');

        self::assertSelectorExists('[data-testid="invoice"]');
        self::assertSelectorTextContains('[data-testid="invoice"]', 'Total');
    }

    public function testChatHasConversations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/app/chat');

        self::assertGreaterThan(0, $crawler->filter('[data-testid="chat-conversation"]')->count());
    }

    public function testFileManagerHasUploadZone(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/app/files');

        self::assertSelectorExists('[data-testid="file-manager"]');
        self::assertGreaterThan(0, $crawler->filter('[data-controller="tailsfadmin--dropzone"]')->count());
    }

    public function testInboxHasMessages(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/app/inbox');

        self::assertGreaterThan(0, $crawler->filter('[data-testid="inbox-item"]')->count());
    }
}
