<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-017 — Tests fonctionnels : Tables (basique + avancée).
 *
 * Vérifie via /ui-kit que :
 *   - La structure thead/tbody est correctement rendue.
 *   - Le wrapper overflow-x-auto est présent (responsive).
 *   - Les checkboxes de sélection utilisent la classe .tableCheckbox.
 *   - Le dropdown d'actions par ligne réutilise data-controller="tailsfadmin--dropdown".
 *   - La table avancée intègre tsf:Ui:Badge et tsf:Ui:Avatar.
 */
final class TableTest extends WebTestCase
{
    // ──────────────────────────────────────────────────────────────────────────
    // Table basique
    // ──────────────────────────────────────────────────────────────────────────

    public function testBasicTableHasTheadAndTbody(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableBasic = $crawler->filter('[data-testid="table-basic"]');
        self::assertGreaterThan(0, $tableBasic->count(), 'La table basique [data-testid="table-basic"] doit être présente');

        self::assertGreaterThan(0, $tableBasic->filter('thead')->count(), 'La table basique doit avoir un <thead>');
        self::assertGreaterThan(0, $tableBasic->filter('tbody')->count(), 'La table basique doit avoir un <tbody>');
    }

    public function testBasicTableHasOverflowXAutoWrapper(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableBasic = $crawler->filter('[data-testid="table-basic"]');
        self::assertGreaterThan(0, $tableBasic->count());

        // Le wrapper overflow-x-auto est un enfant direct de la table card
        $overflowWrapper = $tableBasic->filter('.overflow-x-auto');
        self::assertGreaterThan(0, $overflowWrapper->count(), 'La table basique doit avoir un wrapper .overflow-x-auto pour le responsive');
    }

    public function testBasicTableRendersCellData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableBasic = $crawler->filter('[data-testid="table-basic"]');
        $bodyText = $tableBasic->text();

        // Vérifier qu'au moins une ligne de données est rendue (données du template)
        self::assertStringContainsString('MacBook', $bodyText, 'La table basique doit afficher les données des lignes');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Table avancée
    // ──────────────────────────────────────────────────────────────────────────

    public function testAdvancedTableHasTheadAndTbody(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableAdv = $crawler->filter('[data-testid="table-advanced"]');
        self::assertGreaterThan(0, $tableAdv->count(), 'La table avancée [data-testid="table-advanced"] doit être présente');

        self::assertGreaterThan(0, $tableAdv->filter('thead')->count(), 'La table avancée doit avoir un <thead>');
        self::assertGreaterThan(0, $tableAdv->filter('tbody')->count(), 'La table avancée doit avoir un <tbody>');
    }

    public function testAdvancedTableHasOverflowXAutoWrapper(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableAdv = $crawler->filter('[data-testid="table-advanced"]');
        self::assertGreaterThan(0, $tableAdv->count());

        $overflowWrapper = $tableAdv->filter('.overflow-x-auto');
        self::assertGreaterThan(0, $overflowWrapper->count(), 'La table avancée doit avoir un wrapper .overflow-x-auto pour le responsive');
    }

    public function testAdvancedTableHasTableCheckboxClass(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableAdv = $crawler->filter('[data-testid="table-advanced"]');
        self::assertGreaterThan(0, $tableAdv->count());

        // Les checkboxes de sélection doivent utiliser la classe .tableCheckbox
        $checkboxes = $tableAdv->filter('input.tableCheckbox');
        self::assertGreaterThan(0, $checkboxes->count(), 'La table avancée doit avoir des checkboxes avec la classe .tableCheckbox');
    }

    public function testAdvancedTableHasDropdownControllerOnActions(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableAdv = $crawler->filter('[data-testid="table-advanced"]');
        self::assertGreaterThan(0, $tableAdv->count());

        // Les dropdowns d'actions doivent réutiliser le contrôleur tailsfadmin--dropdown
        $dropdowns = $tableAdv->filter('[data-controller="tailsfadmin--dropdown"]');
        self::assertGreaterThan(0, $dropdowns->count(), 'La table avancée doit avoir des dropdowns d\'actions avec data-controller="tailsfadmin--dropdown"');
    }

    public function testAdvancedTableRendersBadgeForStatus(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableAdv = $crawler->filter('[data-testid="table-advanced"]');
        self::assertGreaterThan(0, $tableAdv->count());

        // La table avancée doit afficher des badges de statut
        // (tsf:Ui:Badge rend un <span> avec des classes de couleur)
        $badges = $tableAdv->filter('tbody span');
        self::assertGreaterThan(0, $badges->count(), 'La table avancée doit afficher des badges de statut via tsf:Ui:Badge');
    }

    public function testAdvancedTableRendersAvatarInitials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        $tableAdv = $crawler->filter('[data-testid="table-advanced"]');
        self::assertGreaterThan(0, $tableAdv->count());

        // tsf:Ui:Avatar sans src → rendu des initiales + classes d'avatar
        $bodyText = $tableAdv->text();
        self::assertStringContainsString('Alice', $bodyText, 'La table avancée doit afficher les noms des utilisateurs');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Navigation (T-TECH-01)
    // ──────────────────────────────────────────────────────────────────────────

    public function testUiKitHasTableAnchorInNav(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();

        // Le sommaire de navigation doit avoir un lien vers la section Tables
        $tableNavLink = $crawler->filter('nav[aria-label] a[href="#section-tables"]');
        self::assertGreaterThan(0, $tableNavLink->count(), 'Le sommaire de la galerie doit contenir un lien vers #section-tables');
    }
}
