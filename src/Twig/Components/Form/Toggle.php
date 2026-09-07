<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Toggle — tsf:Form:Toggle
 * US-014 : interrupteur on/off (switch) stylé via CSS peer (sans Alpine.js).
 * Accessibilité : role="switch" + aria-checked sur l'input natif.
 *
 * Props :
 *   id       (string) — Identifiant HTML (auto-généré si vide)
 *   name     (string) — Attribut name du champ
 *   label    (string) — Libellé visible à côté de l'interrupteur
 *   checked  (bool)   — Activé par défaut (défaut false)
 *   disabled (bool)   — Désactivé (défaut false)
 */
#[AsTwigComponent('tsf:Form:Toggle', template: '@Tailsfadmin/components/Form/Toggle.html.twig')]
final class Toggle
{
    public string $id = '';

    public string $name = '';

    public string $label = '';

    public bool $checked = false;

    public bool $disabled = false;

    public function mount(): void
    {
        if ($this->id === '') {
            $this->id = 'field-' . bin2hex(random_bytes(4));
        }
    }
}
