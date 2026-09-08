<?php

declare(strict_types=1);

namespace App\Tests\E2E\Support;

use Symfony\Component\Panther\Client;

/**
 * Helper d'audit d'accessibilité automatisé (US-025).
 *
 * Injecte axe-core (fixture vendorée, pas de CDN) dans la page pilotée par
 * Panther, lance l'analyse WCAG 2.x niveau A/AA et expose les violations.
 * Le `axe.run` étant asynchrone, on stocke le résultat sur `window` puis on
 * l'interroge en polling synchrone (Panther::executeScript est synchrone).
 */
trait AxeAudit
{
    /** Tags WCAG audités (A + AA, versions 2.0/2.1/2.2). */
    private const AXE_TAGS = ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'];

    /**
     * Lance axe-core sur le document courant et retourne les violations.
     *
     * @return list<array{id: string, impact: ?string, help: string, nodes: array<int, array{target: array<int, string>, html: string}>}>
     */
    private function runAxe(Client $client): array
    {
        $axeSource = file_get_contents(__DIR__ . '/axe.min.js');
        if (false === $axeSource) {
            self::fail('Fixture axe.min.js introuvable.');
        }

        // Injecte axe si absent (idempotent entre deux audits sur la même page).
        $client->executeScript('if (!window.axe) {' . $axeSource . '}');

        // Lance l'analyse et stocke le résultat sur window (axe.run est async).
        $tags = json_encode(self::AXE_TAGS, \JSON_THROW_ON_ERROR);
        $client->executeScript(
            'window.__axeDone = false; window.__axeViolations = [];'
            . 'axe.run(document, { runOnly: { type: "tag", values: ' . $tags . ' } })'
            . '.then(function (r) { window.__axeViolations = r.violations; window.__axeDone = true; })'
            . '.catch(function () { window.__axeDone = true; });'
        );

        // Polling synchrone (max ~10 s).
        for ($i = 0; $i < 50; ++$i) {
            if (true === $client->executeScript('return window.__axeDone === true;')) {
                break;
            }
            usleep(200_000);
        }

        /** @var list<array{id: string, impact: ?string, help: string, nodes: array<int, array{target: array<int, string>, html: string}>}> $violations */
        $violations = $client->executeScript('return window.__axeViolations || [];');

        return $violations;
    }

    /**
     * Échoue si axe-core reporte au moins une violation, avec un rapport lisible.
     */
    private function assertNoAxeViolations(Client $client, string $context = ''): void
    {
        $violations = $this->runAxe($client);

        if ([] === $violations) {
            self::assertTrue(true);

            return;
        }

        $lines = [];
        foreach ($violations as $v) {
            $node = $v['nodes'][0] ?? [];
            $target = $node['target'][0] ?? '?';
            $detail = '';
            // Détail contraste (fg/bg/ratio) si disponible dans les checks axe.
            foreach (($node['any'] ?? []) as $check) {
                if (isset($check['data']['contrastRatio'])) {
                    $detail = sprintf(
                        ' [fg=%s bg=%s ratio=%s attendu=%s]',
                        $check['data']['fgColor'] ?? '?',
                        $check['data']['bgColor'] ?? '?',
                        $check['data']['contrastRatio'] ?? '?',
                        $check['data']['expectedContrastRatio'] ?? '?',
                    );
                    break;
                }
            }
            $lines[] = sprintf(
                '  • [%s] %s — %s (ex: %s)%s',
                $v['impact'] ?? 'n/a',
                $v['id'],
                $v['help'],
                $target,
                $detail,
            );
        }

        self::fail(sprintf(
            "Violations WCAG A/AA détectées par axe-core%s :\n%s",
            '' !== $context ? " ($context)" : '',
            implode("\n", $lines),
        ));
    }
}
