<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Select — tsf:Form:Select
 * US-014 : liste déroulante avec support du mode multiple.
 *
 * Props :
 *   id          (string) — Identifiant HTML (auto-généré si vide)
 *   name        (string) — Attribut name du champ
 *   label       (string) — Libellé du champ (optionnel)
 *   options     (array)  — Tableau [{value: string, label: string}]
 *   placeholder (string) — Texte de l'option vide (ex. "Choisir…")
 *   value       (string) — Valeur sélectionnée par défaut
 *   multiple    (bool)   — Sélection multiple (défaut false)
 *   error       (string) — Message d'erreur (si non vide → état error)
 *   success     (bool)   — État success (défaut false)
 *   disabled    (bool)   — Désactivé (défaut false)
 */
#[AsTwigComponent('tsf:Form:Select', template: '@Tailsfadmin/components/Form/Select.html.twig')]
final class Select
{
    public string $id = '';

    public string $name = '';

    public string $label = '';

    /** @var array<int, array{value: string, label: string}> */
    public array $options = [];

    public string $placeholder = '';

    public string $value = '';

    public bool $multiple = false;

    /** Non vide → état error */
    public string $error = '';

    public bool $success = false;

    public bool $disabled = false;

    public function mount(): void
    {
        if ($this->id === '') {
            $this->id = 'field-' . bin2hex(random_bytes(4));
        }
    }
}
