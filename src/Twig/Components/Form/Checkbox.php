<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Checkbox — tsf:Form:Checkbox
 * US-014 : case à cocher stylée via CSS peer (sans Alpine.js).
 *
 * Props :
 *   id       (string) — Identifiant HTML (auto-généré si vide)
 *   name     (string) — Attribut name du champ
 *   label    (string) — Libellé visible à côté de la case
 *   value    (string) — Valeur soumise quand cochée (défaut "1")
 *   checked  (bool)   — Cochée par défaut (défaut false)
 *   disabled (bool)   — Désactivée (défaut false)
 */
#[AsTwigComponent('tsf:Form:Checkbox', template: '@Tailsfadmin/components/Form/Checkbox.html.twig')]
final class Checkbox
{
    public string $id = '';

    public string $name = '';

    public string $label = '';

    public string $value = '1';

    public bool $checked = false;

    public bool $disabled = false;

    public function mount(): void
    {
        if ($this->id === '') {
            $this->id = 'field-' . bin2hex(random_bytes(4));
        }
    }
}
