<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant ProgressBar — tsf:Ui:ProgressBar.
 *
 * US-036 : jauge de progression accessible (role="progressbar").
 * La valeur est bornée à [0,100] via {@see percent()} (dégradation gracieuse,
 * cohérente avec les autres composants d'affichage — pas d'exception).
 *
 * Utilisation :
 *   <twig:tsf:Ui:ProgressBar :value="72" />
 *   <twig:tsf:Ui:ProgressBar :value="40" variant="success" showValue label="Upload" />
 */
#[AsTwigComponent('tsf:Ui:ProgressBar', template: '@Tailsfadmin/components/Ui/ProgressBar.html.twig')]
final class ProgressBar
{
    /** Valeur de progression (bornée à [0,100] au rendu, cf. {@see percent()}). */
    public int|float $value = 0;

    /** Variante de couleur : brand | success | warning | error. */
    public string $variant = 'brand';

    /** Taille (hauteur) de la barre : sm | md | lg. */
    public string $size = 'md';

    /** Libellé optionnel affiché au-dessus de la barre. */
    public ?string $label = null;

    /** Si true, affiche le pourcentage. */
    public bool $showValue = false;

    /** Pourcentage effectif, borné à l'intervalle [0,100] (entier). */
    public function percent(): int
    {
        return (int) max(0, min(100, round($this->value)));
    }
}
