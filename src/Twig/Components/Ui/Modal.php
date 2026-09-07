<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Modal — tsf:Ui:Modal.
 *
 * US-011 : dialog accessible (focus trap, Échap, clic overlay, restitution focus)
 * via le contrôleur Stimulus "tailsfadmin--modal".
 *
 * Slots :
 *   - trigger : bouton déclencheur (wrappé dans un div data-action="open")
 *   - header  : en-tête de la modale (contient l'élément id="{{ labelId }}")
 *   - body    : contenu principal
 *   - footer  : actions (boutons)
 *
 * Props :
 *   - size    : sm | md | lg | xl (largeur maximale du panel)
 *   - labelId : valeur aria-labelledby (doit correspondre à l'id du titre dans header)
 *
 * Toutes les entrées utilisateur sont auto-échappées par Twig.
 *
 * Utilisation :
 *   <twig:tsf:Ui:Modal size="md" labelId="modal-confirm-title">
 *     <twig:block name="trigger">
 *       <button type="button" class="...">Ouvrir</button>
 *     </twig:block>
 *     <twig:block name="header">
 *       <h3 id="modal-confirm-title">Confirmation</h3>
 *     </twig:block>
 *     <twig:block name="body">...</twig:block>
 *     <twig:block name="footer">...</twig:block>
 *   </twig:tsf:Ui:Modal>
 */
#[AsTwigComponent('tsf:Ui:Modal', template: '@Tailsfadmin/components/Ui/Modal.html.twig')]
final class Modal
{
    /**
     * Taille maximale du panel de la modale.
     * sm | md | lg | xl
     */
    public string $size = 'md';

    /**
     * Valeur de aria-labelledby.
     * Doit correspondre à l'id de l'élément titre dans le slot header.
     */
    public string $labelId = '';
}
