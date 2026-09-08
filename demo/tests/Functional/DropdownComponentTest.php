<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels TDD — US-012 (composant tsf:Ui:Dropdown).
 *
 * Couverture : structure DOM du composant Dropdown (câblage Stimulus,
 * ARIA, role=menu/menuitem).
 *
 * Cycle RED → GREEN :
 *   RED  : écrits avant implémentation du composant Dropdown
 *   GREEN: passent après création de Dropdown.php + Dropdown.html.twig
 *
 * Vérifie que le contrôleur EXISTANT "tailsfadmin--dropdown" (US-007) est
 * réutilisé sans duplication — aucun nouveau contrôleur JS créé.
 *
 * Navigation clavier réelle (flèches, Échap) : couverte par T-TECH-02 (Panther).
 */
final class DropdownComponentTest extends WebTestCase
{
    // ─── Contrôleur Stimulus ─────────────────────────────────────────────────

    public function testDropdownHasStimulusController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-controller~="tailsfadmin--dropdown"]',
            'Le composant Dropdown doit avoir le contrôleur "tailsfadmin--dropdown" (existant, pas de nouveau contrôleur)'
        );
    }

    // ─── Cibles Stimulus ─────────────────────────────────────────────────────

    public function testDropdownTriggerTargetExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--dropdown-target="trigger"]',
            'Le déclencheur du dropdown doit avoir data-tailsfadmin--dropdown-target="trigger"'
        );
    }

    /**
     * Régression : le trigger DOIT porter data-action pour appeler #toggle
     * (le contrôleur n'attache pas l'écouteur lui-même) — sinon le dropdown
     * ne s'ouvre jamais au clic.
     */
    public function testDropdownTriggerHasToggleAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-action*="tailsfadmin--dropdown#toggle"]',
            'Le déclencheur du dropdown doit appeler #toggle via data-action (sinon il ne s\'ouvre pas au clic)'
        );
    }

    public function testDropdownMenuTargetExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--dropdown-target="menu"]',
            'Le panneau du dropdown doit avoir data-tailsfadmin--dropdown-target="menu"'
        );
    }

    // ─── ARIA ────────────────────────────────────────────────────────────────

    public function testDropdownTriggerHasAriaExpanded(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--dropdown-target="trigger"][aria-expanded]',
            'Le déclencheur du dropdown doit avoir l\'attribut aria-expanded'
        );
    }

    public function testDropdownMenuHasRoleMenu(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[role="menu"]',
            'Le panneau du dropdown doit avoir role="menu"'
        );
    }

    public function testDropdownMenuItemsHaveRoleMenuItem(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[role="menuitem"]',
            'Les items du dropdown doivent avoir role="menuitem"'
        );
    }

    // ─── Menu fermé par défaut ───────────────────────────────────────────────

    public function testDropdownMenuIsHiddenByDefault(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--dropdown-target="menu"][hidden]',
            'Le panneau du dropdown doit être masqué par défaut (attribut hidden)'
        );
    }

    // ─── Alignement right ────────────────────────────────────────────────────

    public function testDropdownAlignRightHasRightClass(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--dropdown-target="menu"].right-0',
            'Un Dropdown align="right" doit avoir la classe right-0 sur le panneau'
        );
    }
}
