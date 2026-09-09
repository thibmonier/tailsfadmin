<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-034 — Écrans auth/utilitaires étendus : reset password, 2FA/OTP, 500,
 * maintenance, coming-soon, success. UI de démo (aucune auth réelle).
 */
final class AuthExtendedPagesTest extends WebTestCase
{
    /** @return iterable<string, array{string}> */
    public static function screens(): iterable
    {
        yield 'reset' => ['/auth/reset-password'];
        yield 'new-password' => ['/auth/new-password'];
        yield 'otp' => ['/auth/otp'];
        yield 'success' => ['/auth/success'];
        yield 'maintenance' => ['/auth/maintenance'];
        yield 'coming-soon' => ['/auth/coming-soon'];
        yield '500-preview' => ['/auth/500'];
    }

    #[DataProvider('screens')]
    public function testScreenReturns200(string $route): void
    {
        $client = static::createClient();
        $client->request('GET', $route);

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('h1');
        // Écran auth centré : pas de sidebar admin.
        self::assertSelectorNotExists('aside.sidebar');
    }

    public function testResetPasswordHasEmailField(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/auth/reset-password');

        self::assertGreaterThanOrEqual(1, $crawler->filter('form input[name="email"]')->count());
    }

    public function testOtpHasSegmentedInputsWiredToController(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/auth/otp');

        self::assertSelectorExists('[data-controller="tailsfadmin--otp"]');
        self::assertGreaterThanOrEqual(4, $crawler->filter('[data-tailsfadmin--otp-target="digit"]')->count());
    }

    public function testError500PreviewShowsBusinessPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/auth/500');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', '500');
    }

    public function testRealServerErrorRendersCustom500WithoutStackTraceInProd(): void
    {
        $client = static::createClient(['debug' => false]);
        $client->request('GET', '/_demo/boom');

        self::assertResponseStatusCodeSame(500);
        $content = (string) $client->getResponse()->getContent();
        self::assertStringContainsString('500', $content);
        self::assertStringNotContainsString('Stack Trace', $content);
        self::assertStringNotContainsString('RuntimeException', $content);
    }
}
