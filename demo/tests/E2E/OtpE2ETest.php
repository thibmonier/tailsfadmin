<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

/**
 * E2E — US-034 : saisie OTP segmentée (contrôleur tailsfadmin--otp).
 * Le focus avance à la saisie d'un chiffre et recule sur Backspace à vide.
 */
final class OtpE2ETest extends PantherTestCase
{
    public function testFocusAdvancesOnEntryAndRetreatsOnBackspace(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/auth/otp');

        self::assertSelectorExists('[data-controller="tailsfadmin--otp"]');

        // Saisie d'un chiffre dans le 1er champ → le focus avance au 2e.
        $client->executeScript(<<<'JS'
            const digits = document.querySelectorAll('[data-tailsfadmin--otp-target="digit"]');
            digits[0].focus();
            digits[0].value = '1';
            digits[0].dispatchEvent(new Event('input', { bubbles: true }));
            JS);

        $afterEntry = (int) $client->executeScript(<<<'JS'
            const digits = [...document.querySelectorAll('[data-tailsfadmin--otp-target="digit"]')];
            return digits.indexOf(document.activeElement);
            JS);
        self::assertSame(1, $afterEntry, 'Le focus doit avancer au champ suivant');

        // Backspace sur un champ vide → le focus recule au champ précédent.
        $client->executeScript(<<<'JS'
            const digits = document.querySelectorAll('[data-tailsfadmin--otp-target="digit"]');
            digits[1].focus();
            digits[1].value = '';
            digits[1].dispatchEvent(new KeyboardEvent('keydown', { key: 'Backspace', bubbles: true }));
            JS);

        $afterBackspace = (int) $client->executeScript(<<<'JS'
            const digits = [...document.querySelectorAll('[data-tailsfadmin--otp-target="digit"]')];
            return digits.indexOf(document.activeElement);
            JS);
        self::assertSame(0, $afterBackspace, 'Backspace à vide doit reculer le focus');
    }
}
