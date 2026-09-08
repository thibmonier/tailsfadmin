<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use App\Tests\E2E\Support\AxeAudit;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Audit d'accessibilité automatisé WCAG 2.2 AA (US-025).
 *
 * Exécute axe-core sur les pages assemblées, en mode clair ET sombre.
 * Aucune violation de niveau A/AA ne doit subsister.
 *
 * Suite "e2e" (navigateur réel) — lancé via `composer test:e2e`.
 */
final class AccessibilityE2ETest extends PantherTestCase
{
    use AxeAudit;

    public function testDashboardHasNoViolations(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $this->auditInBothThemes($client, '/', 'dashboard');
    }

    public function testProfileHasNoViolations(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $this->auditInBothThemes($client, '/profile', 'profil');
    }

    public function testProfileModalHasNoViolations(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/profile');

        // Ouvre la première modale d'édition puis audite le dialog.
        $client->executeScript('document.querySelector(\'[data-action*="tailsfadmin--modal#open"]\').click();');
        $client->waitFor('[role="dialog"]:not([hidden])');

        $this->setTheme($client, true);
        $this->assertNoAxeViolations($client, 'profil — modale ouverte (dark)');
    }

    public function testSigninHasNoViolations(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $this->auditInBothThemes($client, '/auth/login', 'connexion');
    }

    public function testSignupHasNoViolations(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $this->auditInBothThemes($client, '/auth/register', 'inscription');
    }

    public function testBlankPageHasNoViolations(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $this->auditInBothThemes($client, '/pages/blank', 'page vierge');
    }

    public function testSidebarSubmenuTogglesAriaExpanded(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/');
        $client->waitFor('[data-tailsfadmin--submenu-target="button"]');

        // Un sous-menu replié existe (aria-expanded="false").
        $selector = '[data-tailsfadmin--submenu-target="button"][aria-expanded="false"]';
        $hasCollapsed = $client->executeScript("return !!document.querySelector('$selector');");
        self::assertTrue($hasCollapsed, 'Au moins un sous-menu doit être replié au chargement.');

        // Après clic, il se déplie (aria-expanded passe à true, le <ul> n'est plus hidden).
        $state = $client->executeScript(
            "var b = document.querySelector('$selector'); var ul = b.parentElement.querySelector('ul'); b.click();"
            . 'return { expanded: b.getAttribute("aria-expanded"), hidden: ul.hasAttribute("hidden") };'
        );
        self::assertSame('true', $state['expanded'], 'aria-expanded doit passer à true après clic.');
        self::assertFalse($state['hidden'], 'Le sous-menu déplié ne doit plus porter l\'attribut hidden.');
    }

    /**
     * Garde anti-régression de la dette dark-mode (rétro S6) : les surfaces
     * `dark:bg-gray-*` doivent rester SOMBRES en dark (l'inversion des gris,
     * si elle réapparaissait, les rendrait claires).
     */
    public function testDarkSurfacesRemainDark(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/profile');

        $client->executeScript('document.querySelector(\'[data-action*="tailsfadmin--modal#open"]\').click();');
        $client->waitFor('[role="dialog"]:not([hidden])');
        $this->setTheme($client, true);

        $result = $client->executeScript(<<<'JS'
            var isDark = function (c) { var m = c.match(/\d+/g); return m ? (+m[0] + +m[1] + +m[2]) / 3 < 90 : false; };
            var dialog = document.querySelector('[role="dialog"]:not([hidden])');
            var input = dialog.querySelector('input');
            var panel = dialog.querySelector('.rounded-2xl, .rounded-3xl');
            return {
                input: isDark(getComputedStyle(input).backgroundColor),
                panel: isDark(getComputedStyle(panel).backgroundColor),
            };
            JS);

        self::assertTrue($result['input'], 'En dark, le fond des inputs doit rester sombre.');
        self::assertTrue($result['panel'], 'En dark, le panneau de la modale doit rester sombre.');
    }

    private function auditInBothThemes(Client $client, string $url, string $label): void
    {
        $client->request('GET', $url);

        $this->setTheme($client, false);
        $this->assertNoAxeViolations($client, "$label (clair)");

        $this->setTheme($client, true);
        $this->assertNoAxeViolations($client, "$label (dark)");
    }

    /** Bascule le thème et force un recalcul des styles avant l'audit. */
    private function setTheme(Client $client, bool $dark): void
    {
        $client->executeScript(sprintf(
            "document.documentElement.classList.%s('dark'); void document.documentElement.offsetHeight;",
            $dark ? 'add' : 'remove',
        ));
        usleep(400_000);
    }
}
