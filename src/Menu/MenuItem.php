<?php

declare(strict_types=1);

namespace Tailsfadmin\Menu;

/**
 * Valeur immuable représentant un élément de menu.
 *
 * label      : libellé affiché dans la sidebar
 * path       : chemin URL (ex. '/', '/calendar')
 * icon       : clé d'icône SVG (ex. 'dashboard', 'calendar')
 * children   : sous-éléments pour les menus à plusieurs niveaux
 * permission : attribut de sécurité optionnel (ex. 'ROLE_ADMIN', 'view:reports').
 *              Si défini, l'item n'est rendu que si l'utilisateur courant y est
 *              autorisé (is_granted). Null = toujours visible. Le filtrage est
 *              opéré par le MenuBuilder ; MenuItem n'expose que la donnée.
 */
final readonly class MenuItem
{
    /** @param MenuItem[] $children */
    public function __construct(
        public string $label,
        public string $path,
        public string $icon,
        public array $children = [],
        public ?string $permission = null,
    ) {
    }
}
