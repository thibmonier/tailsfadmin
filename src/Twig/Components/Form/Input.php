<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Input générique — tsf:Form:Input
 * US-014 : champ de saisie texte avec gestion des états.
 *
 * Props :
 *   id          (string) — Identifiant HTML (auto-généré si vide)
 *   name        (string) — Attribut name du champ
 *   label       (string) — Libellé du champ (optionnel)
 *   type        (string) — Type HTML : text|email|password|number|search|tel|url (défaut text)
 *   placeholder (string) — Placeholder
 *   value       (string) — Valeur courante
 *   error       (string) — Message d'erreur (si non vide → état error + aria-invalid)
 *   success     (bool)   — État success (défaut false)
 *   disabled    (bool)   — Désactivé (défaut false)
 *   required    (bool)   — Champ obligatoire (défaut false)
 */
#[AsTwigComponent('tsf:Form:Input', template: '@Tailsfadmin/components/Form/Input.html.twig')]
final class Input
{
    public string $id = '';

    public string $name = '';

    public string $label = '';

    /** text|email|password|number|search|tel|url */
    public string $type = 'text';

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

        $allowedTypes = ['text', 'email', 'password', 'number', 'search', 'tel', 'url'];
        if (!\in_array($this->type, $allowedTypes, true)) {
            throw new \LogicException(\sprintf(
                'Type d\'input "%s" non supporté. Types valides : %s.',
                $this->type,
                \implode(', ', $allowedTypes)
            ));
        }
    }
}
