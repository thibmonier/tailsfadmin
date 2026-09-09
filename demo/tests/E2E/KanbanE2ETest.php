<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

/**
 * E2E — US-040 : Kanban avec glisser-déposer HTML5 natif (tailsfadmin--kanban).
 *
 * Le DnD HTML5 est simulé en dispatchant dragstart → dragover → drop avec un
 * DataTransfer partagé (les mouvements souris WebDriver ne déclenchent pas les
 * événements HTML5 natifs). L'alternative clavier « Déplacer vers … » est aussi
 * vérifiée, ainsi que l'annonce aria-live.
 */
final class KanbanE2ETest extends PantherTestCase
{
    public function testDragCardBetweenColumnsUpdatesCounts(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/tasks/kanban');

        self::assertSame('3', $this->count($client, 'todo'));
        self::assertSame('2', $this->count($client, 'done'));

        $client->executeScript(<<<'JS'
            const card = document.querySelector('[data-tailsfadmin--kanban-target="list"][data-status="todo"] [data-card-id]');
            const target = document.querySelector('[data-tailsfadmin--kanban-target="list"][data-status="done"]');
            const dt = new DataTransfer();
            card.dispatchEvent(new DragEvent('dragstart', { bubbles: true, cancelable: true, dataTransfer: dt }));
            target.dispatchEvent(new DragEvent('dragover', { bubbles: true, cancelable: true, dataTransfer: dt }));
            target.dispatchEvent(new DragEvent('drop', { bubbles: true, cancelable: true, dataTransfer: dt }));
            card.dispatchEvent(new DragEvent('dragend', { bubbles: true, cancelable: true, dataTransfer: dt }));
            JS);

        $client->waitForElementToContain('[data-testid="kanban-count"][data-status="done"]', '3');

        self::assertSame('2', $this->count($client, 'todo'), 'La colonne « À faire » perd une carte');
        self::assertSame('3', $this->count($client, 'done'), 'La colonne « Terminé » gagne une carte');
    }

    public function testKeyboardMoveUpdatesCountsAndAnnounces(): void
    {
        $client = static::createPantherClient(['browser' => static::CHROME]);
        $client->request('GET', '/tasks/kanban');

        $client->executeScript(<<<'JS'
            const card = document.querySelector('[data-tailsfadmin--kanban-target="list"][data-status="todo"] [data-card-id]');
            const btn = card.querySelector('[data-action="tailsfadmin--kanban#moveTo"][data-status="done"]');
            btn.click();
            JS);

        $client->waitForElementToContain('[data-testid="kanban-count"][data-status="done"]', '3');
        self::assertSame('2', $this->count($client, 'todo'));

        $announce = $client->executeScript(
            "return document.querySelector('[data-tailsfadmin--kanban-target=\"announcer\"]').textContent;"
        );
        self::assertStringContainsString('déplacée vers', (string) $announce, 'Le déplacement doit être annoncé (aria-live)');
    }

    private function count(object $client, string $status): string
    {
        return trim((string) $client->executeScript(
            "return document.querySelector('[data-testid=\"kanban-count\"][data-status=\"{$status}\"]').textContent;"
        ));
    }
}
