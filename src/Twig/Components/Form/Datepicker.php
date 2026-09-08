<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Datepicker — tsf:Form:Datepicker.
 *
 * US-015 : input stylé TailAdmin (reprend les classes d'US-014)
 * câblé au contrôleur Stimulus "tailsfadmin--datepicker" (flatpickr vendoré).
 *
 * Options transmises via data-values au contrôleur :
 *   - mode        : "single" | "range" | "multiple"
 *   - dateFormat  : format flatpickr (ex. "Y-m-d", "d/m/Y")
 *   - enableTime  : afficher le sélecteur d'heure
 *
 * Utilisation :
 *   <twig:tsf:Form:Datepicker
 *       label="Date de naissance"
 *       placeholder="Choisir une date"
 *   />
 *   <twig:tsf:Form:Datepicker
 *       label="Période"
 *       mode="range"
 *       placeholder="Début → Fin"
 *   />
 */
#[AsTwigComponent('tsf:Form:Datepicker', template: '@Tailsfadmin/components/Form/Datepicker.html.twig')]
final class Datepicker
{
    /** Libellé affiché au-dessus de l'input. */
    public string $label = '';

    /** Attribut name de l'input. */
    public string $name = '';

    /** Valeur initiale (format correspondant à dateFormat). */
    public string $value = '';

    /** Texte d'invite. */
    public string $placeholder = 'Sélectionner une date';

    /**
     * Mode flatpickr.
     * single | range | multiple
     */
    public string $mode = 'single';

    /**
     * Format de date flatpickr (ex. "Y-m-d", "d/m/Y").
     */
    public string $dateFormat = 'Y-m-d';

    /** Afficher le sélecteur d'heure. */
    public bool $enableTime = false;

    /** Désactiver le champ. */
    public bool $disabled = false;

    /** ID de l'input (généré si vide). */
    public string $id = '';

    public function mount(): void
    {
        if ($this->id === '') {
            $this->id = 'datepicker-' . bin2hex(random_bytes(4));
        }
    }
}
