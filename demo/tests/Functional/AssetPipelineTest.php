<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-002 (pipeline Tailwind v4 + AssetMapper).
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant la création du layout base.html.twig + tailwind:build
 *   GREEN: passent après le build CSS et le template avec <link>
 *
 * DoD US-002 :
 *   - GET / : la balise <link> du CSS est présente
 *   - Le CSS compilé existe et contient ".dark"
 *   - 0 erreur 404 sur l'asset CSS
 */
final class AssetPipelineTest extends WebTestCase
{
    public function testHomePageContainsCssLinkTag(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('link[rel="stylesheet"]', 'La page doit contenir un <link rel="stylesheet"> pour le CSS Tailwind');
    }

    public function testCssAssetIsLoadedWithoutErrors(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        // Récupère l'href du premier lien stylesheet
        $linkNode = $crawler->filter('link[rel="stylesheet"]');
        self::assertGreaterThan(0, $linkNode->count(), 'Aucun <link rel="stylesheet"> trouvé');

        $cssHref = $linkNode->first()->attr('href');
        self::assertNotNull($cssHref, 'L\'attribut href du <link> est null');
        self::assertNotEmpty($cssHref, 'L\'attribut href du <link> est vide');

        // Le href doit pointer vers le CSS compilé (pas 404)
        $client->request('GET', $cssHref);
        self::assertResponseIsSuccessful(
            sprintf('Le CSS "%s" retourne une erreur (attendu: 200)', $cssHref)
        );
    }

    public function testCompiledCssContainsDarkTokens(): void
    {
        // Ce test vérifie que le CSS compilé contient les tokens dark mode (.dark)
        // Il dépend de tailwind:build ayant été lancé.
        $buildDir = \dirname(__DIR__, 2) . '/public/assets';

        // Trouver le fichier CSS compilé dans public/assets
        $cssFiles = array_filter(
            glob($buildDir . '/styles/app-*.css') ?: [],
            'is_file'
        );

        if (empty($cssFiles)) {
            // Fichier pas encore compilé — test skippé en dev avant premier build
            // En CI, tailwind:build est lancé avant les tests
            self::markTestSkipped(
                'CSS Tailwind non compilé — lancer `php bin/console tailwind:build` d\'abord.'
            );
        }

        $cssContent = file_get_contents(reset($cssFiles));
        self::assertIsString($cssContent);
        self::assertStringContainsString(
            '.dark',
            $cssContent,
            'Le CSS compilé doit contenir les déclarations .dark (tokens dark mode)'
        );
    }
}
