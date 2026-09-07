<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel TDD — US-001/T-001-05 (cycle RED → GREEN).
 *
 * Critères DoD :
 *   - GET / → HTTP 200
 *   - La réponse contient la chaîne "tailsfadmin"
 *
 * Ce test doit être écrit AVANT l'implémentation du contrôleur (RED),
 * puis passer au vert une fois le contrôleur et le template créés (GREEN).
 */
final class HomeControllerTest extends WebTestCase
{
    public function testHomePageReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
    }

    public function testHomePageContainsTailsfadminString(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'tailsfadmin');
    }
}
