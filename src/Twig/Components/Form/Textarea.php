<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Textarea — tsf:Form:Textarea
 * US-014 : zone de texte multi-lignes avec gestion des états.
 *
 * Props :
 *   id          (string) — Identifiant HTML (auto-généré si vide)
 *   name        (string) — Attribut name du champ
 *   label       (string) — Libellé du champ (optionnel)
 *   rows        (int)    — Nombre de lignes visibles (défaut 4)
 *   placeholder (string) — Placeholder
 *   value       (string) — Valeur courante
 *   error       (string) — Message d'erreur (si non vide → état error)
 *   success     (bool)   — État success (défaut false)
 *   disabled    (bool)   — Désactivé (défaut false)
 *   required    (bool)   — Champ obligatoire (défaut false)
 */
#[AsTwigComponent('tsf:Form:Textarea', template: '@Tailsfadmin/components/Form/Textarea.html.twig')]
final class Textarea
{
    public string $id = '';

    public string $name = '';

    public string $label = '';

    public int $rows = 4;

    public string $placeholder = '';

    public string $value = '';

    /** Non vide → état error */
    public string $error = '';

    public bool $success = false;

    public bool $disabled = false;

    public bool $required = false;

    public function mount(): void
    {
        if ($this->id === '') {
            $this->id = 'field-' . bin2hex(random_bytes(4));
        }
    }
}
