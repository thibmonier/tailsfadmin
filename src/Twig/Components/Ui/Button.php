<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Button — tsf:Ui:Button.
 *
 * US-010 : 6 variantes, 2 tailles, icônes, état loading (aria-busy) et disabled.
 * Si `href` est fourni, rend un <a> ; sinon un <button>.
 *
 * Toutes les entrées sont auto-échappées par Twig.
 *
 * Utilisation :
 *   <twig:tsf:Ui:Button variant="primary">Sauvegarder</twig:tsf:Ui:Button>
 *   <twig:tsf:Ui:Button variant="secondary" href="/dashboard">Tableau de bord</twig:tsf:Ui:Button>
 *   <twig:tsf:Ui:Button variant="danger" loading>Suppression…</twig:tsf:Ui:Button>
 */
#[AsTwigComponent('tsf:Ui:Button', template: '@Tailsfadmin/components/Ui/Button.html.twig')]
final class Button
{
    /**
     * Variante visuelle.
     * primary | secondary | success | danger | ghost | link
     */
    public string $variant = 'primary';

    /**
     * Taille du bouton.
     * sm | lg
     */
    public string $size = 'sm';

    /**
     * SVG de l'icône à afficher avant le texte (passé en HTML brut interne).
     * N'accepte pas d'entrée utilisateur arbitraire — usage interne uniquement.
     */
    public string $iconStart = '';

    /**
     * SVG de l'icône à afficher après le texte (idem, usage interne).
     */
    public string $iconEnd = '';

    /** Si true, affiche un spinner et positionne aria-busy="true". */
    public bool $loading = false;

    /** Désactive le bouton (attribut disabled + aria-disabled). */
    public bool $disabled = false;

    /**
     * Si fourni, rend un <a href="…"> au lieu d'un <button>.
     * L'URL est échappée par Twig.
     */
    public string $href = '';

    /**
     * Type HTML du bouton (<button type="…">).
     * Ignoré si href est fourni.
     */
    public string $type = 'button';
}
