<?php

declare(strict_types=1);

namespace Tailsfadmin\Menu;

/**
 * Valeur immuable représentant un groupe de menu dans la sidebar.
 *
 * Un groupe possède un libellé (affiché en h3) et une liste d'items.
 * Exemple : groupe "Menu" contenant Dashboard, Calendrier…
 */
final readonly class MenuGroup
{
    /** @param MenuItem[] $items */
    public function __construct(
        public string $label,
        public array $items,
    ) {
    }
}
