<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-040 (inc. 2) — Tests fonctionnels : vue Kanban (/tasks/kanban).
 *
 * Structure serveur (colonnes, cartes, compteurs, câblage du contrôleur Stimulus
 * et alternative clavier). Le glisser-déposer réel est couvert par l'E2E Panther.
 */
final class TasksKanbanTest extends WebTestCase
{
    private const ROUTE = '/tasks/kanban';

    public function testPageReturns200AndRendersAdminLayout(): void
    {
        $client = static::createClient();
        $client->request('GET', self::ROUTE);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'tailsfadmin');
    }

    public function testFourColumnsAndAllCardsRendered(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        self::assertCount(4, $crawler->filter('[data-testid="kanban-column"]'));
        self::assertCount(8, $crawler->filter('[data-testid="kanban-card"]'));
    }

    public function testColumnCountersMatchSeededDistribution(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        $countFor = static fn (string $status): string => trim(
            $crawler->filter('[data-testid="kanban-count"][data-status="' . $status . '"]')->text()
        );

        self::assertSame('3', $countFor('todo'));
        self::assertSame('2', $countFor('in_progress'));
        self::assertSame('1', $countFor('review'));
        self::assertSame('2', $countFor('done'));
    }

    public function testStimulusWiringAndKeyboardAlternativeArePresent(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        self::assertSelectorExists('[data-controller="tailsfadmin--kanban"]');
        self::assertSelectorExists('[data-tailsfadmin--kanban-target="announcer"][aria-live="polite"]');

        // Chaque carte propose 3 cibles de déplacement (les 3 autres colonnes).
        self::assertCount(24, $crawler->filter('[data-action="tailsfadmin--kanban#moveTo"]'));
    }

    public function testCardsAreDraggable(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::ROUTE);

        $crawler->filter('[data-testid="kanban-card"]')->each(function ($card): void {
            self::assertSame('true', $card->attr('draggable'));
            self::assertNotEmpty($card->attr('data-card-id'));
        });
    }
}
