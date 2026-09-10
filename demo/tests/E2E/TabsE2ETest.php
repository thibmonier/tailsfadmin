<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Facebook\WebDriver\WebDriverKeys;
use Symfony\Component\Panther\PantherTestCase;

/**
 * E2E — US-036 / T-036-10 : montage réel du contrôleur tailsfadmin--tabs.
 *
 * Prouve au navigateur que :
 *   1. l'onglet actif par défaut (profil) affiche son panneau, les autres masqués ;
 *   2. un clic sur « Sécurité » bascule le panneau + met à jour aria-selected ;
 *   3. la navigation clavier (Flèche droite) active l'onglet suivant.
 */
final class TabsE2ETest extends PantherTestCase
{
    public function testTabsSwitchOnClickAndKeyboard(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/ui-kit');

        // État initial : profil visible, secu masqué.
        self::assertSelectorExists('#tsf-panel-profil:not([hidden])');
        self::assertSelectorExists('#tsf-panel-secu[hidden]');

        // Clic sur l'onglet « Sécurité ».
        $client->executeScript(
            "document.querySelector('[role=\"tab\"][data-tab-id=\"secu\"]').click();"
        );
        $client->waitFor('#tsf-panel-secu:not([hidden])');

        self::assertSelectorExists(
            '#tsf-panel-secu:not([hidden])',
            'Après clic, le panneau « Sécurité » doit être visible',
        );
        self::assertSelectorExists(
            '#tsf-panel-profil[hidden]',
            'Après clic, le panneau « Profil » doit être masqué',
        );

        $ariaSelected = $client->executeScript(
            "return document.querySelector('[role=\"tab\"][data-tab-id=\"secu\"]').getAttribute('aria-selected');"
        );
        self::assertSame('true', $ariaSelected, 'L\'onglet actif doit porter aria-selected="true"');

        // Navigation clavier : Flèche droite depuis « Sécurité » → « Notifications ».
        $client->executeScript(
            "document.querySelector('[role=\"tab\"][data-tab-id=\"secu\"]').focus();"
        );
        $client->getKeyboard()->pressKey(WebDriverKeys::ARROW_RIGHT);
        $client->waitFor('#tsf-panel-notif:not([hidden])');

        self::assertSelectorExists(
            '#tsf-panel-notif:not([hidden])',
            'Flèche droite doit activer l\'onglet suivant (Notifications)',
        );
    }

    /**
     * Régression : sur la variante « segmented », le clic doit basculer le panneau
     * ET déplacer le surlignage (pastille blanche), piloté par aria-selected.
     */
    public function testSegmentedActiveStateAndHighlightFollowSelection(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/ui-kit');

        // État initial : Overview actif.
        self::assertSelectorExists('#tsf-panel-overview:not([hidden])');

        // Clic sur « Analytics » dans la démo segmentée.
        $client->executeScript(
            "document.querySelector('[data-testid=\"tabs-segmented-demo\"] [role=\"tab\"][data-tab-id=\"analytics\"]').click();"
        );
        $client->waitFor('#tsf-panel-analytics:not([hidden])');

        // Le contenu bascule…
        self::assertSelectorExists('#tsf-panel-analytics:not([hidden])');
        self::assertSelectorExists('#tsf-panel-overview[hidden]');

        // …et l'état ARIA suit.
        $ariaSelected = $client->executeScript(
            "return document.querySelector('[data-testid=\"tabs-segmented-demo\"] [role=\"tab\"][data-tab-id=\"analytics\"]').getAttribute('aria-selected');"
        );
        self::assertSame('true', $ariaSelected);

        // …et le surlignage aussi : l'onglet actif a un fond blanc (pastille).
        $activeBg = $client->executeScript(
            "return getComputedStyle(document.querySelector('[data-testid=\"tabs-segmented-demo\"] [role=\"tab\"][data-tab-id=\"analytics\"]')).backgroundColor;"
        );
        self::assertStringContainsString('255, 255, 255', (string) $activeBg, 'L\'onglet actif doit être surligné (fond blanc)');

        // L'ancien onglet n'est plus surligné (fond transparent).
        $inactiveBg = $client->executeScript(
            "return getComputedStyle(document.querySelector('[data-testid=\"tabs-segmented-demo\"] [role=\"tab\"][data-tab-id=\"overview\"]')).backgroundColor;"
        );
        self::assertStringContainsString('0, 0, 0, 0', (string) $inactiveBg, 'L\'onglet inactif ne doit pas être surligné');
    }
}
