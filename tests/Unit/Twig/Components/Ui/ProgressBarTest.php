<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Unit\Twig\Components\Ui;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tailsfadmin\Twig\Components\Ui\ProgressBar;

/**
 * US-036 / T-036-01 — Composant ProgressBar.
 *
 * Le pourcentage est borné à [0,100] (dégradation gracieuse, cohérente avec les
 * autres composants d'affichage du bundle — pas d'exception sur une valeur hors
 * bornes).
 */
final class ProgressBarTest extends TestCase
{
    #[DataProvider('valuesProvider')]
    public function testPercentIsClampedBetween0And100(int|float $value, int $expected): void
    {
        $bar = new ProgressBar();
        $bar->value = $value;

        self::assertSame($expected, $bar->percent());
    }

    /**
     * @return iterable<string, array{int|float, int}>
     */
    public static function valuesProvider(): iterable
    {
        yield 'valeur nominale' => [72, 72];
        yield 'borne basse' => [0, 0];
        yield 'borne haute' => [100, 100];
        yield 'au-dessus → clampé à 100' => [140, 100];
        yield 'négatif → clampé à 0' => [-5, 0];
        yield 'flottant arrondi' => [33.6, 34];
    }

    public function testDefaults(): void
    {
        $bar = new ProgressBar();

        self::assertSame(0, $bar->percent());
        self::assertSame('brand', $bar->variant);
        self::assertSame('md', $bar->size);
        self::assertFalse($bar->showValue);
        self::assertNull($bar->label);
    }
}
