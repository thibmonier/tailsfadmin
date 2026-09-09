<?php

declare(strict_types=1);

namespace Tailsfadmin\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Tailsfadmin\Command\MakePageCommand;

/**
 * US-035 — Tests du générateur `make:tailsfadmin-page`.
 *
 * Génère dans un répertoire temporaire et vérifie : fichiers créés, contenu
 * conforme (route + template étendant le layout admin), validité syntaxique PHP,
 * gabarit inconnu rejeté, non-écrasement sans --force.
 */
final class MakePageCommandTest extends TestCase
{
    private string $projectDir;

    protected function setUp(): void
    {
        $this->projectDir = sys_get_temp_dir() . '/tsf-make-' . uniqid('', true);
        mkdir($this->projectDir, 0o777, true);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->projectDir);
    }

    public function testGeneratesDashboardPage(): void
    {
        $tester = $this->tester();
        $exit = $tester->execute(['name' => 'Sales', '--template' => 'dashboard']);

        self::assertSame(0, $exit);

        $controller = $this->projectDir . '/src/Controller/SalesController.php';
        $template = $this->projectDir . '/templates/sales.html.twig';
        self::assertFileExists($controller);
        self::assertFileExists($template);

        $controllerCode = (string) file_get_contents($controller);
        self::assertStringContainsString('final class SalesController extends AbstractController', $controllerCode);
        self::assertStringContainsString("name: 'sales'", $controllerCode);
        self::assertStringContainsString("'/sales'", $controllerCode);
        self::assertStringContainsString('declare(strict_types=1);', $controllerCode);

        $templateCode = (string) file_get_contents($template);
        self::assertStringContainsString("@Tailsfadmin/layout/admin.html.twig", $templateCode);

        // Le code PHP généré est syntaxiquement valide.
        self::assertSame(0, $this->phpLint($controller), 'Le contrôleur généré doit être du PHP valide');
    }

    public function testKebabNameIsNormalized(): void
    {
        $tester = $this->tester();
        $tester->execute(['name' => 'sales-report', '--template' => 'table']);

        self::assertFileExists($this->projectDir . '/src/Controller/SalesReportController.php');
        self::assertFileExists($this->projectDir . '/templates/sales_report.html.twig');

        $code = (string) file_get_contents($this->projectDir . '/src/Controller/SalesReportController.php');
        self::assertStringContainsString("name: 'sales_report'", $code);
        self::assertStringContainsString("'/sales-report'", $code);
    }

    public function testUnknownTemplateFailsAndListsChoices(): void
    {
        $tester = $this->tester();
        $exit = $tester->execute(['name' => 'Foo', '--template' => 'nope']);

        self::assertNotSame(0, $exit);
        $output = $tester->getDisplay();
        self::assertStringContainsString('blank', $output);
        self::assertStringContainsString('dashboard', $output);
    }

    public function testDoesNotOverwriteWithoutForce(): void
    {
        $this->tester()->execute(['name' => 'Sales', '--template' => 'blank']);

        $second = $this->tester();
        $exit = $second->execute(['name' => 'Sales', '--template' => 'blank']);
        self::assertNotSame(0, $exit, 'Sans --force, un fichier existant ne doit pas être écrasé');

        $third = $this->tester();
        $exit = $third->execute(['name' => 'Sales', '--template' => 'blank', '--force' => true]);
        self::assertSame(0, $exit, 'Avec --force, l\'écrasement est autorisé');
    }

    private function tester(): CommandTester
    {
        return new CommandTester(new MakePageCommand($this->projectDir));
    }

    private function phpLint(string $file): int
    {
        exec(\sprintf('%s -l %s 2>&1', escapeshellarg(\PHP_BINARY), escapeshellarg($file)), $out, $code);

        return $code;
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($dir);
    }
}
