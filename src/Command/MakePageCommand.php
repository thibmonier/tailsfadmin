<?php

declare(strict_types=1);

namespace Tailsfadmin\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * US-035 — Scaffolding `make:tailsfadmin-page`.
 *
 * Génère, dans l'application hôte, un contrôleur + un template étendant le layout
 * admin du bundle, à partir d'un gabarit (blank, dashboard, table, form). Le code
 * généré respecte PSR-12 / PHPStan (declare(strict_types), classe finale, typé).
 *
 * Non-interactif : tous les arguments peuvent être passés en ligne de commande.
 */
#[AsCommand(
    name: 'make:tailsfadmin-page',
    description: 'Génère une page (contrôleur + template) conforme au thème, depuis un gabarit',
)]
final class MakePageCommand extends Command
{
    private const TEMPLATES = ['blank', 'dashboard', 'table', 'form'];

    public function __construct(private readonly string $projectDir)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de la page (ex. Sales, sales-report)')
            ->addOption('template', 't', InputOption::VALUE_REQUIRED, 'Gabarit : ' . implode(' | ', self::TEMPLATES), 'blank')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Écrase les fichiers existants');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $template */
        $template = $input->getOption('template');
        if (!\in_array($template, self::TEMPLATES, true)) {
            $io->error(\sprintf('Gabarit « %s » inconnu. Gabarits disponibles : %s.', $template, implode(', ', self::TEMPLATES)));

            return Command::INVALID;
        }

        /** @var string $rawName */
        $rawName = $input->getArgument('name');
        $parts = $this->words($rawName);
        if ([] === $parts) {
            $io->error('Le nom de la page est vide ou invalide.');

            return Command::INVALID;
        }

        $class = implode('', array_map('ucfirst', $parts)) . 'Controller';
        $snake = implode('_', $parts);
        $kebab = implode('-', $parts);
        $force = true === $input->getOption('force');

        $controllerPath = $this->projectDir . '/src/Controller/' . $class . '.php';
        $templatePath = $this->projectDir . '/templates/' . $snake . '.html.twig';

        foreach ([$controllerPath, $templatePath] as $path) {
            if (is_file($path) && !$force) {
                $io->error(\sprintf('Le fichier « %s » existe déjà. Relancez avec --force pour l\'écraser.', $path));

                return Command::FAILURE;
            }
        }

        $this->write($controllerPath, $this->controllerStub($class, $snake, $kebab));
        $this->write($templatePath, $this->templateStub($template, $snake, ucfirst($parts[0])));

        $io->success(\sprintf('Page « %s » générée (gabarit : %s).', $snake, $template));
        $io->listing([
            'src/Controller/' . $class . '.php',
            'templates/' . $snake . '.html.twig',
            'Route : ' . $snake . ' → /' . $kebab,
        ]);

        return Command::SUCCESS;
    }

    /**
     * Découpe un nom en mots normalisés (minuscules), en gérant camelCase,
     * kebab-case et snake_case. « SalesReport » / « sales-report » → ['sales','report'].
     *
     * @return list<string>
     */
    private function words(string $name): array
    {
        $spaced = preg_replace('/(?<!^)([A-Z])/', ' $1', $name) ?? $name;
        $parts = preg_split('/[^a-zA-Z0-9]+/', $spaced) ?: [];

        return array_values(array_filter(
            array_map('strtolower', $parts),
            static fn (string $p): bool => '' !== $p,
        ));
    }

    private function write(string $path, string $content): void
    {
        $dir = \dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0o777, true);
        }
        file_put_contents($path, $content);
    }

    private function controllerStub(string $class, string $route, string $kebab): string
    {
        return <<<PHP
            <?php

            declare(strict_types=1);

            namespace App\\Controller;

            use Symfony\\Bundle\\FrameworkBundle\\Controller\\AbstractController;
            use Symfony\\Component\\HttpFoundation\\Response;
            use Symfony\\Component\\Routing\\Attribute\\Route;

            final class {$class} extends AbstractController
            {
                #[Route('/{$kebab}', name: '{$route}', methods: ['GET'])]
                public function index(): Response
                {
                    return \$this->render('{$route}.html.twig');
                }
            }

            PHP;
    }

    private function templateStub(string $template, string $route, string $pageName): string
    {
        $body = match ($template) {
            'dashboard' => $this->dashboardBody(),
            'table' => $this->tableBody(),
            'form' => $this->formBody(),
            default => $this->blankBody(),
        };

        return <<<TWIG
            {% extends '@Tailsfadmin/layout/admin.html.twig' %}

            {% block title %}{$pageName} — tailsfadmin{% endblock %}

            {% block breadcrumb %}
                <twig:tsf:Layout:Breadcrumb pageName="{$pageName}" />
            {% endblock %}

            {% block content %}
            {$body}
            {% endblock %}

            TWIG;
    }

    private function blankBody(): string
    {
        return <<<TWIG
                <twig:tsf:Ui:Card title="Nouvelle page">
                    <twig:block name="body">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Contenu à compléter.</p>
                    </twig:block>
                </twig:tsf:Ui:Card>
            TWIG;
    }

    private function dashboardBody(): string
    {
        return <<<TWIG
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
                    {% for i in 1..4 %}
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                            <span class="text-theme-sm text-gray-500 dark:text-gray-400">Indicateur {{ i }}</span>
                            <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">0</h4>
                        </div>
                    {% endfor %}
                </div>

                <div class="mt-6">
                    <twig:tsf:Ui:Card title="Évolution">
                        <twig:block name="body">
                            <twig:tsf:Chart:Line
                                :series="[{name: 'Série', data: [12, 19, 15, 22, 30, 28]}]"
                                :categories="['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin']"
                                type="area"
                                :height="300"
                            />
                        </twig:block>
                    </twig:tsf:Ui:Card>
                </div>
            TWIG;
    }

    private function tableBody(): string
    {
        return <<<TWIG
                <twig:tsf:Ui:Table
                    title="Éléments"
                    :headers="['Nom', 'Statut', 'Date']"
                    :rows="[['Exemple A', 'Actif', '01/01/2026'], ['Exemple B', 'En attente', '02/01/2026']]"
                    :striped="true"
                />
            TWIG;
    }

    private function formBody(): string
    {
        return <<<TWIG
                <twig:tsf:Ui:Card title="Formulaire">
                    <twig:block name="body">
                        <form method="post" class="space-y-5">
                            <twig:tsf:Form:Input label="Nom" name="name" :required="true" />
                            <twig:tsf:Form:Input label="E-mail" name="email" type="email" :required="true" />
                            <div class="flex justify-end">
                                <twig:tsf:Ui:Button variant="primary" type="submit">Enregistrer</twig:tsf:Ui:Button>
                            </div>
                        </form>
                    </twig:block>
                </twig:tsf:Ui:Card>
            TWIG;
    }
}
