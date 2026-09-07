<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * T-TECH-01 — Smoke-test CSS compilé (garde-fou #1).
 *
 * Contexte : au Sprint 2, le layout s'affichait non stylé sans qu'aucun test
 * ne l'attrape. Ce test vérifie que le CSS compilé contient bien les classes
 * sémantiques de composant (.menu-item) et les utilitaires structurels (flex, fixed).
 *
 * Critères :
 *   - Le fichier CSS compilé existe (résolu via manifest.json).
 *   - Contient au moins une occurrence de ".menu-item" (classe composant portée).
 *   - Contient "flex" et "fixed" (utilitaires Tailwind structurels).
 *
 * Le test échoue si l'une manque, attrapant ainsi une régression de port CSS
 * ou une mauvaise configuration de @source.
 */
final class CssBuildTest extends WebTestCase
{
    /**
     * Résout le chemin du CSS compilé depuis le manifest AssetMapper,
     * puis vérifie la présence des classes de garde-fou.
     */
    public function testCompiledCssContainsSemanticAndStructuralClasses(): void
    {
        // demo/ est deux niveaux au-dessus de tests/Functional/
        $demoDir = \dirname(__DIR__, 2);
        $manifestPath = $demoDir.'/public/assets/manifest.json';

        self::assertFileExists($manifestPath, 'Le manifest AssetMapper doit exister (lancer tailwind:build puis asset-map:compile)');

        $raw = file_get_contents($manifestPath);
        self::assertIsString($raw);

        /** @var array<string,string> $manifest */
        $manifest = json_decode($raw, true);
        self::assertIsArray($manifest);
        self::assertArrayHasKey('styles/app.css', $manifest, 'L\'entrée "styles/app.css" doit être dans le manifest');

        // Le manifest stocke la URL publique (/assets/styles/app-HASH.css)
        $publicRelative = $manifest['styles/app.css'];
        $cssPath = $demoDir.'/public'.$publicRelative;

        self::assertFileExists($cssPath, sprintf('Le fichier CSS compilé "%s" doit exister', $cssPath));

        $css = file_get_contents($cssPath);
        self::assertIsString($css);
        self::assertNotEmpty($css, 'Le CSS compilé ne doit pas être vide');

        // Garde-fou #1 : classe sémantique de composant (portée depuis le bundle)
        self::assertStringContainsString(
            '.menu-item',
            $css,
            'CSS compilé : ".menu-item" absent — le port CSS des classes de composant (@layer components) a régressé'
        );

        // Garde-fou #2 : utilitaires Tailwind structurels
        self::assertStringContainsString(
            'flex',
            $css,
            'CSS compilé : "flex" absent — les utilitaires Tailwind de base ont régressé'
        );

        self::assertStringContainsString(
            'fixed',
            $css,
            'CSS compilé : "fixed" absent — les utilitaires Tailwind de positionnement ont régressé'
        );

        // T-TECH-02 — Smoke CSS étendu : formulaire + table
        self::assertStringContainsString(
            '.form-check-input',
            $css,
            'CSS compilé : ".form-check-input" absent — le port CSS des classes de formulaire a régressé'
        );

        self::assertStringContainsString(
            '.tableCheckbox',
            $css,
            'CSS compilé : ".tableCheckbox" absent — le port CSS des classes de table a régressé'
        );
    }
}
