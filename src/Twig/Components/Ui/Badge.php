<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Badge — tsf:Ui:Badge.
 *
 * US-009 : 7 variantes de couleur + taille.
 * Toutes les entrées sont auto-échappées par Twig.
 *
 * Utilisation :
 *   <twig:tsf:Ui:Badge color="success">Actif</twig:tsf:Ui:Badge>
 *   <twig:tsf:Ui:Badge color="error" size="sm">Erreur</twig:tsf:Ui:Badge>
 */
#[AsTwigComponent('tsf:Ui:Badge', template: '@Tailsfadmin/components/Ui/Badge.html.twig')]
final class Badge
{
    /**
     * Variante de couleur : primary | success | error | warning | info | light | dark
     */
    public string $color = 'primary';

    /**
     * Taille du badge.
     * sm = plus compact, md = taille par défaut
     */
    public string $size = 'md';
}
