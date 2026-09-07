<?php

declare(strict_types=1);

namespace Tailsfadmin\Menu;

/**
 * Service MenuBuilder — construit la structure de menu depuis la configuration.
 *
 * Reçoit la liste brute des groupes (array depuis tailsfadmin.yaml) et produit
 * des objets MenuGroup/MenuItem typés, prêts à être consommés par Twig.
 *
 * Configuration attendue (format YAML) :
 *
 *   tailsfadmin:
 *     menu:
 *       - group: Menu
 *         items:
 *           - label: Dashboard
 *             path: /
 *             icon: dashboard
 *             children: []
 */
final class MenuBuilder
{
    /** @var list<array<string, mixed>> */
    private array $config;

    /** @param list<array<string, mixed>> $config */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Retourne les groupes de menu construits depuis la configuration.
     *
     * @return MenuGroup[]
     */
    public function getGroups(): array
    {
        $groups = [];

        foreach ($this->config as $groupConfig) {
            $label = $this->str($groupConfig['group'] ?? '');

            /** @var list<array<string, mixed>> $rawItems */
            $rawItems = isset($groupConfig['items']) && is_array($groupConfig['items'])
                ? array_values($groupConfig['items'])
                : [];

            $groups[] = new MenuGroup(
                label: $label,
                items: $this->buildItems($rawItems),
            );
        }

        return $groups;
    }

    /**
     * @param  list<array<string, mixed>> $itemsConfig
     * @return MenuItem[]
     */
    private function buildItems(array $itemsConfig): array
    {
        $items = [];

        foreach ($itemsConfig as $itemConfig) {
            /** @var list<array<string, mixed>> $rawChildren */
            $rawChildren = isset($itemConfig['children']) && is_array($itemConfig['children'])
                ? array_values($itemConfig['children'])
                : [];

            $items[] = new MenuItem(
                label:    $this->str($itemConfig['label'] ?? ''),
                path:     $this->str($itemConfig['path'] ?? '#'),
                icon:     $this->str($itemConfig['icon'] ?? ''),
                children: $this->buildItems($rawChildren),
            );
        }

        return $items;
    }

    /**
     * Convertit une valeur scalaire en chaîne de caractères.
     * Retourne une chaîne vide pour les valeurs non scalaires.
     */
    private function str(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }
}
