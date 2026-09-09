<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Ribbon — tsf:Ui:Ribbon.
 *
 * US-036 : ruban d'angle ou étiquette, à placer dans un conteneur positionné
 * (`relative overflow-hidden`). Purement présentationnel (aucun JS).
 *
 * Utilisation :
 *   <div class="relative overflow-hidden rounded-2xl …">
 *       <twig:tsf:Ui:Ribbon text="Nouveau" />
 *       …
 *   </div>
 */
#[AsTwigComponent('tsf:Ui:Ribbon', template: '@Tailsfadmin/components/Ui/Ribbon.html.twig')]
final class Ribbon
{
    /** Texte du ruban. */
    public string $text = '';

    /** Variante de couleur : brand | success | warning | error. */
    public string $variant = 'brand';

    /** Position : top-left | top-right. */
    public string $position = 'top-right';

    /** Forme : corner (diagonal) | rounded (étiquette). */
    public string $shape = 'corner';
}
