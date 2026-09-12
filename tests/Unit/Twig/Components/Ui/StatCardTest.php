<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit\Twig\Components\Ui;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tailsfadmin\Twig\Components\Ui\StatCard;

/**
 * US-087 / G1 — Composant StatCard.
 *
 * La progression est bornée à [0,100] (dégradation gracieuse, cohérente avec
 * ProgressBar) et vaut null quand aucune progression n'est fournie.
 */
final class StatCardTest extends TestCase
{
    public function testDefaults(): void
    {
        $card = new StatCard();

        self::assertSame('', $card->label);
        self::assertSame('', $card->value);
        self::assertSame('brand', $card->variant);
        self::assertSame('neutral', $card->trend);
        self::assertNull($card->delta);
        self::assertNull($card->progress);
        self::assertNull($card->hint);
        self::assertNull($card->href);
        self::assertNull($card->percent());
    }

    #[DataProvider('progressProvider')]
    public function testPercentIsClampedOrNull(int|float|null $progress, ?int $expected): void
    {
        $card = new StatCard();
        $card->progress = $progress;

        self::assertSame($expected, $card->percent());
    }

    /**
     * @return iterable<string, array{int|float|null, int|null}>
     */
    public static function progressProvider(): iterable
    {
        yield 'absente → null' => [null, null];
        yield 'valeur nominale' => [80, 80];
        yield 'borne basse' => [0, 0];
        yield 'borne haute' => [100, 100];
        yield 'au-dessus → clampé à 100' => [140, 100];
        yield 'négatif → clampé à 0' => [-10, 0];
        yield 'flottant arrondi' => [61.4, 61];
    }
}
