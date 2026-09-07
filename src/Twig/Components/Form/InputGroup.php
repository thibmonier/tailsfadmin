<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant InputGroup — tsf:Form:InputGroup
 * US-014 : champ de saisie avec préfixe/suffixe (slots).
 *
 * Props :
 *   id          (string) — Identifiant HTML (auto-généré si vide)
 *   name        (string) — Attribut name du champ
 *   label       (string) — Libellé du champ (optionnel)
 *   type        (string) — Type HTML : text|email|password|number|search|tel|url (défaut text)
 *   placeholder (string) — Placeholder
 *   value       (string) — Valeur courante
 *   error       (string) — Message d'erreur (si non vide → état error)
 *   success     (bool)   — État success (défaut false)
 *   disabled    (bool)   — Désactivé (défaut false)
 *
 * Slots (blocks Twig) :
 *   prefix — Contenu à gauche du champ (icône, texte, …)
 *   suffix — Contenu à droite du champ (icône, texte, …)
 */
#[AsTwigComponent('tsf:Form:InputGroup', template: '@Tailsfadmin/components/Form/InputGroup.html.twig')]
final class InputGroup
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

    public function mount(): void
    {
        if ($this->id === '') {
            $this->id = 'field-' . bin2hex(random_bytes(4));
        }
    }
}
