<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-013 — Tests fonctionnels pour Card, MediaCard, GridImage et Video.
 *
 * Stratégie : on passe par la page /ui-kit qui intègre tous les composants.
 * Chaque test cible un composant précis via des sélecteurs CSS.
 */
final class CardMediaTest extends WebTestCase
{
    // ──────────────────────────────────────────────────────────────────────────
    // tsf:Ui:Card
    // ──────────────────────────────────────────────────────────────────────────

    public function testCardRendersTitle(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="card-basic"]');
        self::assertSelectorTextContains('[data-testid="card-basic"] .card-title', 'Statistiques');
    }

    public function testCardRendersHeaderBodyFooterSlots(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertSelectorExists('[data-testid="card-slots"] .card-header');
        self::assertSelectorExists('[data-testid="card-slots"] .card-body');
        self::assertSelectorExists('[data-testid="card-slots"] .card-footer');
    }

    public function testCardWithoutShadowAndBorder(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        // La Card sans ombre/bordure ne doit pas avoir la classe shadow-theme-sm
        $card = $crawler->filter('[data-testid="card-no-shadow"]');
        self::assertCount(1, $card);
        self::assertStringNotContainsString('shadow-theme', $card->attr('class') ?? '');
        self::assertStringNotContainsString('border', $card->attr('class') ?? '');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // tsf:Ui:MediaCard
    // ──────────────────────────────────────────────────────────────────────────

    public function testMediaCardRendersImageWithAlt(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $img = $crawler->filter('[data-testid="media-card"] img');
        self::assertCount(1, $img);
        $alt = $img->attr('alt');
        self::assertNotNull($alt, 'L\'attribut alt doit être présent sur l\'image de la MediaCard');
        self::assertNotSame('', $alt, 'L\'attribut alt ne doit pas être vide');
    }

    public function testMediaCardHasActionsSlot(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertSelectorExists('[data-testid="media-card"] .media-card-actions');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // tsf:Ui:GridImage
    // ──────────────────────────────────────────────────────────────────────────

    public function testGridImageSingleColumnRendersImage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $grid = $crawler->filter('[data-testid="grid-1col"] img');
        self::assertGreaterThanOrEqual(1, $grid->count());
    }

    public function testGridImage2ColRendersImagesWithAlt(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $images = $crawler->filter('[data-testid="grid-2col"] img');
        self::assertGreaterThanOrEqual(2, $images->count());

        foreach ($images as $img) {
            $alt = $img->getAttribute('alt');
            self::assertNotNull($alt, 'Chaque image de la grid doit avoir un attribut alt');
            self::assertNotSame('', $alt, 'L\'attribut alt ne doit pas être vide');
        }
    }

    public function testGridImage3ColRendersImagesWithAlt(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $images = $crawler->filter('[data-testid="grid-3col"] img');
        self::assertGreaterThanOrEqual(3, $images->count());

        foreach ($images as $img) {
            $alt = $img->getAttribute('alt');
            self::assertNotNull($alt, 'Chaque image de la grid doit avoir un attribut alt');
            self::assertNotSame('', $alt, 'L\'attribut alt ne doit pas être vide');
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // tsf:Ui:Video
    // ──────────────────────────────────────────────────────────────────────────

    public function testVideoIframeHasTitleAndHttpsSrc(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $iframes = $crawler->filter('[data-testid="video-section"] iframe');
        self::assertGreaterThanOrEqual(1, $iframes->count());

        foreach ($iframes as $iframe) {
            $title = $iframe->getAttribute('title');
            self::assertNotNull($title, 'L\'iframe vidéo doit avoir un attribut title pour l\'accessibilité');
            self::assertNotSame('', $title, 'L\'attribut title de l\'iframe ne doit pas être vide');

            $src = $iframe->getAttribute('src');
            self::assertNotNull($src, 'L\'iframe doit avoir un attribut src');
            self::assertStringStartsWith('https://', $src, 'L\'URL de l\'embed vidéo doit utiliser HTTPS');
        }
    }

    public function testVideoIframeHasAllowfullscreen(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        $iframe = $crawler->filter('[data-testid="video-section"] iframe')->first();
        self::assertCount(1, $iframe);
        self::assertNotNull($iframe->attr('allowfullscreen'));
    }

    public function testVideoComponentThrowsOnHttpSrc(): void
    {
        self::expectException(\LogicException::class);
        self::expectExceptionMessage('L\'URL de l\'embed vidéo doit utiliser HTTPS');

        // Instanciation directe — mount() valide l'URL sans passer par le container
        $video = new \Tailsfadmin\Twig\Components\Ui\Video();
        $video->src = 'http://www.youtube.com/embed/dQw4w9WgXcQ';
        $video->mount();
    }
}
