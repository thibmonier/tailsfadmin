<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Dropdown — tsf:Ui:Dropdown.
 *
 * US-012 : Twig Component réutilisable câblé au contrôleur Stimulus EXISTANT
 * "tailsfadmin--dropdown" (créé en US-007). Aucun nouveau contrôleur JS.
 *
 * Slots :
 *   - trigger : bouton déclencheur (obligatoire, fourni entre balises)
 *   - menu    : liste d'items role="menuitem" (fournie entre balises)
 *
 * Le composant est réutilisable hors header (DRY par rapport au markup inline
 * du Header.html.twig).
 *
 * Utilisation :
 *   <twig:tsf:Ui:Dropdown align="right">
 *     <twig:block name="trigger">
 *       <button type="button">Ouvrir</button>
 *     </twig:block>
 *     <twig:block name="menu">
 *       <a role="menuitem" href="/">Accueil</a>
 *     </twig:block>
 *   </twig:tsf:Ui:Dropdown>
 */
#[AsTwigComponent('tsf:Ui:Dropdown', template: '@Tailsfadmin/components/Ui/Dropdown.html.twig')]
final class Dropdown
{
    /**
     * Alignement du menu par rapport au déclencheur.
     * left | right
     */
    public string $align = 'left';
}
