<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant StatCard — tsf:Ui:StatCard.
 *
 * Gap G1 (US-087) : carte KPI (icône + label + valeur), avec variation (delta/trend)
 * et barre de progression optionnelles. L'icône est fournie via le slot `icon`
 * (SVG libre), pour ne pas dépendre du jeu restreint de tsf_icon().
 *
 * Utilisation :
 *   <twig:tsf:Ui:StatCard label="Heures cette semaine" value="21,5 h">
 *       <twig:block name="icon">…svg…</twig:block>
 *   </twig:tsf:Ui:StatCard>
 *
 *   <twig:tsf:Ui:StatCard label="Complétude" value="80 %" :progress="80" variant="warning" />
 *   <twig:tsf:Ui:StatCard label="CA" value="1,2 M€" delta="+8 %" trend="up" variant="success" />
 */
#[AsTwigComponent('tsf:Ui:StatCard', template: '@Tailsfadmin/components/Ui/StatCard.html.twig')]
final class StatCard
{
    /** Libellé du KPI (au-dessus de la valeur). */
    public string $label = '';

    /** Valeur du KPI (déjà formatée : "21,5 h", "80 %", …). */
    public string $value = '';

    /** Teinte de l'icône : brand | success | warning | error. */
    public string $variant = 'brand';

    /** Variation optionnelle (ex. "+8 %", "1 sem. à finir"). */
    public ?string $delta = null;

    /** Sens de la variation : up | down | neutral (couleur du delta). */
    public string $trend = 'neutral';

    /** Progression optionnelle [0,100] ; si non nulle, affiche une barre. */
    public int|float|null $progress = null;

    /** Sous-texte optionnel (ex. "Projeté : 10,5 j"). */
    public ?string $hint = null;

    /** Lien optionnel : rend la carte cliquable. */
    public ?string $href = null;

    /** Progression bornée à [0,100] (entier), ou null si absente. */
    public function percent(): ?int
    {
        if (null === $this->progress) {
            return null;
        }

        return (int) max(0, min(100, round($this->progress)));
    }
}
