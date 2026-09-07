<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Tailsfadmin\Menu\MenuBuilder;
use Tailsfadmin\Menu\MenuGroup;

#[AsTwigComponent('tsf:Layout:Sidebar', template: '@Tailsfadmin/components/Layout/Sidebar.html.twig')]
final class Sidebar
{
    public function __construct(
        private readonly MenuBuilder $menuBuilder,
    ) {
    }

    /**
     * Groupes de menu exposés à Twig.
     *
     * @return MenuGroup[]
     */
    public function getGroups(): array
    {
        return $this->menuBuilder->getGroups();
    }
}
