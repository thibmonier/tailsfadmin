<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Card générique — tsf:Ui:Card
 * US-013 : conteneur avec titre, description et slots header/body/footer.
 *
 * Props :
 *   title    (string)  — Titre de la carte (optionnel)
 *   subtitle (string)  — Sous-titre / description (optionnel)
 *   padding  (string)  — Taille du padding interne : sm|md|lg (défaut md)
 *   shadow   (bool)    — Ombre portée (défaut true)
 *   border   (bool)    — Bordure (défaut true)
 *
 * Slots (blocks Twig) :
 *   header — Entête personnalisé (remplace l'entête titre/sous-titre si renseigné)
 *   body   — Corps principal de la carte
 *   footer — Pied de carte (actions, méta…)
 */
#[AsTwigComponent('tsf:Ui:Card', template: '@Tailsfadmin/components/Ui/Card.html.twig')]
final class Card
{
    public string $title = '';

    public string $subtitle = '';

    /** sm|md|lg */
    public string $padding = 'md';

    public bool $shadow = true;

    public bool $border = true;
}
