<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant PageHeader — tsf:Layout:PageHeader.
 *
 * Gap G4 (US-087) : bandeau d'en-tête de page = titre (h1) + sous-titre optionnel
 * + slot `actions` (aligné à droite). Un slot `breadcrumb` optionnel accueille
 * <twig:tsf:Layout:Breadcrumb />.
 *
 * Utilisation :
 *   <twig:tsf:Layout:PageHeader title="Bonjour, Camille" subtitle="Semaine 38">
 *       <twig:block name="actions"><twig:tsf:Ui:Button>Saisir</twig:tsf:Ui:Button></twig:block>
 *   </twig:tsf:Layout:PageHeader>
 */
#[AsTwigComponent('tsf:Layout:PageHeader', template: '@Tailsfadmin/components/Layout/PageHeader.html.twig')]
final class PageHeader
{
    /** Titre de la page (rendu en <h1>). */
    public string $title = '';

    /** Sous-titre optionnel (contexte : date, périmètre…). */
    public ?string $subtitle = null;
}
