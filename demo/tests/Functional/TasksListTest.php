<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-040 (inc. 1) — Tests fonctionnels : vue liste des tâches (/tasks).
 *
 * Table de tâches factices, filtrable (statut / priorité) et triable (titre,
 * échéance, priorité) côté serveur via query string. Dégradation totale sans JS.
 */
final class TasksListTest extends WebTestCase
{
    private const ROUTE = '/tasks';

    public function testPageReturns200AndRendersAdminLayout(): void
    {
        $client = static::createClient();
        $client->request('GET', self::ROUTE);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'tailsfadmin');
    }

    public function testListsAllSeededTasksByDefault(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        self::assertSelectorExists('[data-testid="tasks-table"]');
        self::assertCount(8, $crawler->filter('[data-testid="task-row"]'));
    }

    public function testFilterByStatusTodo(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE . '?status=todo');

        self::assertCount(3, $crawler->filter('[data-testid="task-row"]'));
        // Toutes les lignes affichées portent le statut « À faire ».
        $crawler->filter('[data-testid="task-row"] [data-testid="task-status"]')->each(function ($cell): void {
            self::assertStringContainsString('À faire', $cell->text());
        });
    }

    public function testFilterByPriorityHigh(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE . '?priority=high');

        self::assertCount(3, $crawler->filter('[data-testid="task-row"]'));
    }

    public function testCombinedFilters(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE . '?status=todo&priority=high');

        // Seule « Corriger le bug de connexion » est à faire ET haute priorité.
        self::assertCount(1, $crawler->filter('[data-testid="task-row"]'));
        self::assertSelectorTextContains('[data-testid="task-row"]', 'Corriger le bug de connexion');
    }

    public function testUnknownFilterValueIsIgnoredGracefully(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE . '?status=n_importe_quoi');

        self::assertResponseIsSuccessful();
        self::assertCount(8, $crawler->filter('[data-testid="task-row"]'));
    }

    public function testSortByTitleAscendingThenDescending(): void
    {
        $client = static::createClient();

        $asc = $client->request('GET', self::ROUTE . '?sort=title&dir=asc');
        $firstAsc = $asc->filter('[data-testid="task-row"] [data-testid="task-title"]')->first()->text();
        self::assertStringContainsString('Archiver les anciens logs', $firstAsc);

        $desc = $client->request('GET', self::ROUTE . '?sort=title&dir=desc');
        $firstDesc = $desc->filter('[data-testid="task-row"] [data-testid="task-title"]')->first()->text();
        self::assertStringNotContainsString('Archiver les anciens logs', $firstDesc);
    }

    public function testFilterFormAndSortLinksArePresent(): void
    {
        $client = static::createClient();
        $client->request('GET', self::ROUTE);

        self::assertSelectorExists('[data-testid="tasks-filter"]');
        self::assertSelectorExists('[data-testid="sort-title"]');
        self::assertSelectorExists('[data-testid="sort-due"]');
    }
}
