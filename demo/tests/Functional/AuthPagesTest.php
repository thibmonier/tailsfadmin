<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel — US-023 : pages d'authentification + utilitaires.
 *
 * Couvre signin, signup, page vierge, et la page 404 personnalisée rendue
 * en mode production (kernel.debug=false) SANS stack trace exposée.
 * UI de démo uniquement (pas d'authentification réelle).
 */
final class AuthPagesTest extends WebTestCase
{
    public function testSigninRendersForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/auth/login');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Connexion');
        self::assertGreaterThanOrEqual(1, $crawler->filter('form input[name="email"]')->count());
        self::assertGreaterThanOrEqual(1, $crawler->filter('form input[name="password"]')->count());
        self::assertSelectorTextContains('body', 'Se connecter');
    }

    public function testSignupRendersForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/auth/register');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Inscription');
        self::assertGreaterThanOrEqual(1, $crawler->filter('form input[name="first_name"]')->count());
        self::assertGreaterThanOrEqual(1, $crawler->filter('form input[name="last_name"]')->count());
        self::assertGreaterThanOrEqual(1, $crawler->filter('form input[name="email"]')->count());
        self::assertGreaterThanOrEqual(1, $crawler->filter('form input[name="password"]')->count());
    }

    public function testBlankPageRendersInAdminLayout(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pages/blank');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Page vierge');
        // Hérite du layout admin → sidebar présente.
        self::assertGreaterThanOrEqual(1, $crawler->filter('aside')->count());
    }

    public function testNotFoundRendersCustom404WithoutStackTraceInProd(): void
    {
        // debug=false : Symfony utilise le template d'erreur métier (comme en prod).
        $client = static::createClient(['debug' => false]);
        $client->request('GET', '/cette-page-n-existe-pas-du-tout');

        self::assertResponseStatusCodeSame(404);
        self::assertSelectorTextContains('body', 'introuvable');
        self::assertSelectorTextContains('body', 'Retour à l\'accueil');

        // Aucune stack trace / détail technique exposé en prod.
        $content = (string) $client->getResponse()->getContent();
        self::assertStringNotContainsString('NotFoundHttpException', $content);
        self::assertStringNotContainsString('Stack Trace', $content);
    }
}
