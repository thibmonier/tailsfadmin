<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Tabs — tsf:Ui:Tabs.
 *
 * US-036 : onglets accessibles (pattern ARIA tablist/tab/tabpanel), pilotés par
 * le contrôleur Stimulus `tailsfadmin--tabs`. Les panneaux sont fournis en slots
 * nommés par l'id d'onglet.
 *
 * `active` invalide (ou null) retombe sur le premier onglet ({@see activeId()}).
 *
 * Utilisation :
 *   <twig:tsf:Ui:Tabs :items="[{id:'profil',label:'Profil'},{id:'secu',label:'Sécurité'}]">
 *     <twig:block name="profil">…</twig:block>
 *     <twig:block name="secu">…</twig:block>
 *   </twig:tsf:Ui:Tabs>
 */
#[AsTwigComponent('tsf:Ui:Tabs', template: '@Tailsfadmin/components/Ui/Tabs.html.twig')]
final class Tabs
{
    /**
     * Onglets : liste de {id, label, icon?}.
     *
     * @var list<array{id: string, label: string, icon?: string}>
     */
    public array $items = [];

    /** Id de l'onglet actif (défaut = premier onglet). */
    public ?string $active = null;

    /** Style visuel : underline | segmented | pill | boxed. */
    public string $variant = 'underline';

    /** Id de l'onglet effectivement actif : `active` s'il existe, sinon le premier, sinon null. */
    public function activeId(): ?string
    {
        if ([] === $this->items) {
            return null;
        }

        $ids = array_column($this->items, 'id');

        if (null !== $this->active && \in_array($this->active, $ids, true)) {
            return $this->active;
        }

        return $ids[0];
    }
}
