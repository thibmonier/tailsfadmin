<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant MediaCard — tsf:Ui:MediaCard
 * US-013 : carte avec image en en-tête, corps et slot d'actions.
 *
 * Props :
 *   mediaSrc    (string) — URL de l'image (obligatoire)
 *   mediaAlt    (string) — Texte alternatif de l'image (obligatoire, contrôlé en template)
 *   mediaAspect (string) — Ratio de l'image : 16/9|4/3|1/1 (défaut 16/9)
 *   title       (string) — Titre de la carte (optionnel)
 *   subtitle    (string) — Sous-titre (optionnel)
 *
 * Slots (blocks Twig) :
 *   body    — Corps de la carte
 *   actions — Boutons / liens d'action (zone footer)
 */
#[AsTwigComponent('tsf:Ui:MediaCard', template: '@Tailsfadmin/components/Ui/MediaCard.html.twig')]
final class MediaCard
{
    public string $mediaSrc = '';

    public string $mediaAlt = '';

    /** 16/9|4/3|1/1 */
    public string $mediaAspect = '16/9';

    public string $title = '';

    public string $subtitle = '';
}
