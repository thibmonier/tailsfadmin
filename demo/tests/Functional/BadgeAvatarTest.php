<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-009 (composants tsf:Ui:Badge et tsf:Ui:Avatar).
 *
 * Couverture : rendu DOM des badges (7 variantes) et avatars (tailles, statut, initiales).
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant implémentation des composants Badge et Avatar
 *   GREEN: passent après création des fichiers PHP + Twig correspondants
 */
final class BadgeAvatarTest extends WebTestCase
{
    // ─── Badge : variantes de couleur ────────────────────────────────────────

    public function testBadgePrimaryHasCorrectClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-brand-50.text-brand-700',
            'Le badge "primary" doit avoir les classes bg-brand-50 et text-brand-700'
        );
    }

    public function testBadgeSuccessHasCorrectClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-success-50.text-success-700',
            'Le badge "success" doit avoir les classes bg-success-50 et text-success-700'
        );
    }

    public function testBadgeErrorHasCorrectClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-error-50.text-error-700',
            'Le badge "error" doit avoir les classes bg-error-50 et text-error-700'
        );
    }

    public function testBadgeWarningHasCorrectClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-warning-50.text-warning-700',
            'Le badge "warning" doit avoir les classes bg-warning-50 et text-warning-700'
        );
    }

    public function testBadgeInfoHasCorrectClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-blue-light-50.text-blue-light-700',
            'Le badge "info" doit avoir les classes bg-blue-light-50 et text-blue-light-700'
        );
    }

    public function testBadgeLightHasCorrectClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-gray-100.text-gray-700',
            'Le badge "light" doit avoir les classes bg-gray-100 et text-gray-700'
        );
    }

    public function testBadgeDarkHasCorrectClasses(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-gray-500.text-white',
            'Le badge "dark" doit avoir les classes bg-gray-500 et text-white'
        );
    }

    // ─── Badge : structure ──────────────────────────────────────────────────

    public function testBadgeRendersAsSpan(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'span.rounded-full',
            'Le badge doit être rendu comme un <span> avec la classe rounded-full'
        );
    }

    // ─── Avatar : image ──────────────────────────────────────────────────────

    public function testAvatarWithImageRendersImg(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            'img.rounded-full',
            'L\'avatar avec image doit avoir un <img class="rounded-full">'
        );
    }

    // ─── Avatar : initiales ──────────────────────────────────────────────────

    public function testAvatarWithoutImageShowsInitials(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-brand-100.rounded-full',
            'L\'avatar sans image doit afficher les initiales avec bg-brand-100'
        );
    }

    // ─── Avatar : point de statut ────────────────────────────────────────────

    public function testAvatarOnlineStatusDotExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '.bg-success-500.rounded-full',
            'L\'avatar "online" doit avoir un point de statut bg-success-500'
        );
    }
}
