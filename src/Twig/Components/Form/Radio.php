<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Radio — tsf:Form:Radio
 * US-014 : bouton radio stylé via CSS peer (sans Alpine.js).
 *
 * Props :
 *   id       (string) — Identifiant HTML (auto-généré si vide)
 *   name     (string) — Attribut name (commun à tous les boutons d'un groupe)
 *   label    (string) — Libellé visible à côté du bouton
 *   value    (string) — Valeur soumise quand sélectionné
 *   checked  (bool)   — Sélectionné par défaut (défaut false)
 *   disabled (bool)   — Désactivé (défaut false)
 */
#[AsTwigComponent('tsf:Form:Radio', template: '@Tailsfadmin/components/Form/Radio.html.twig')]
final class Radio
{
    public string $id = '';

    public string $name = '';

    public string $label = '';

    public string $value = '';

    public bool $checked = false;

    public bool $disabled = false;

    public function mount(): void
    {
        if ($this->id === '') {
            $this->id = 'field-' . bin2hex(random_bytes(4));
        }
    }
}
