<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * US-040 — Vues « Tâches » : liste (triable/filtrable) et Kanban.
 *
 * Données STRICTEMENT factices, définies dans le contrôleur. La vue liste filtre
 * et trie côté serveur via la query string (dégradation totale sans JS).
 *
 * @phpstan-type Task array{id:string, title:string, assignee:string, status:string, priority:string, due:string, dueTs:int}
 */
final class TasksController extends AbstractController
{
    private const STATUSES = ['todo', 'in_progress', 'review', 'done'];
    private const PRIORITIES = ['high', 'medium', 'low'];
    private const SORTS = ['title', 'due', 'priority', 'status'];
    private const PRIORITY_WEIGHT = ['high' => 3, 'medium' => 2, 'low' => 1];
    private const STATUS_WEIGHT = ['todo' => 1, 'in_progress' => 2, 'review' => 3, 'done' => 4];

    #[Route('/tasks', name: 'tasks', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $status = (string) $request->query->get('status', 'all');
        $priority = (string) $request->query->get('priority', 'all');
        $sort = (string) $request->query->get('sort', 'due');
        $dir = strtolower((string) $request->query->get('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Normalisation (whitelist) : toute valeur inconnue → « all » / défaut.
        $status = \in_array($status, self::STATUSES, true) ? $status : 'all';
        $priority = \in_array($priority, self::PRIORITIES, true) ? $priority : 'all';
        $sort = \in_array($sort, self::SORTS, true) ? $sort : 'due';

        $tasks = $this->seedTasks();

        $tasks = array_values(array_filter($tasks, static function (array $task) use ($status, $priority): bool {
            if ('all' !== $status && $task['status'] !== $status) {
                return false;
            }

            return 'all' === $priority || $task['priority'] === $priority;
        }));

        usort($tasks, static function (array $a, array $b) use ($sort, $dir): int {
            $cmp = match ($sort) {
                'title' => strnatcasecmp($a['title'], $b['title']),
                'priority' => self::PRIORITY_WEIGHT[$a['priority']] <=> self::PRIORITY_WEIGHT[$b['priority']],
                'status' => self::STATUS_WEIGHT[$a['status']] <=> self::STATUS_WEIGHT[$b['status']],
                default => $a['dueTs'] <=> $b['dueTs'],
            };

            return 'desc' === $dir ? -$cmp : $cmp;
        });

        return $this->render('tasks/list.html.twig', [
            'tasks' => $tasks,
            'filters' => ['status' => $status, 'priority' => $priority],
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    /**
     * @return list<Task>
     */
    private function seedTasks(): array
    {
        return [
            $this->task('t1', 'Rédiger la spec API', 'Alice Dupont', 'in_progress', 'high', '2026-06-15'),
            $this->task('t2', 'Corriger le bug de connexion', 'Bob Martin', 'todo', 'high', '2026-06-10'),
            $this->task('t3', 'Revue de code paiement', 'Carol Denis', 'review', 'medium', '2026-06-12'),
            $this->task('t4', 'Mettre à jour la documentation', 'David Leroy', 'todo', 'low', '2026-06-20'),
            $this->task('t5', 'Déployer en staging', 'Eric Legrand', 'done', 'medium', '2026-06-11'),
            $this->task('t6', 'Optimiser les requêtes SQL', 'Fanny Roux', 'in_progress', 'high', '2026-06-18'),
            $this->task('t7', 'Préparer la démo client', 'Alice Dupont', 'todo', 'medium', '2026-06-14'),
            $this->task('t8', 'Archiver les anciens logs', 'Bob Martin', 'done', 'low', '2026-06-25'),
        ];
    }

    /**
     * @return Task
     */
    private function task(string $id, string $title, string $assignee, string $status, string $priority, string $due): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'assignee' => $assignee,
            'status' => $status,
            'priority' => $priority,
            'due' => \DateTimeImmutable::createFromFormat('Y-m-d', $due)->format('d/m/Y'),
            'dueTs' => (int) (\DateTimeImmutable::createFromFormat('Y-m-d', $due)->format('U')),
        ];
    }
}
