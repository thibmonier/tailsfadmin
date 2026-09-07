<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant GridImage — tsf:Ui:GridImage
 * US-013 : affichage d'une liste d'images en grille responsive.
 *
 * Props :
 *   images  (array)  — Tableau de tableaux associatifs :
 *                       [ ['src' => '...', 'alt' => '...', 'caption' => '...?'], ... ]
 *                       L'attribut 'alt' est obligatoire et non vide.
 *   columns (int)    — Nombre de colonnes : 1|2|3 (défaut 1)
 *
 * Variantes :
 *   columns=1 → image pleine largeur (image-01 source)
 *   columns=2 → grille 2 colonnes (image-02 source)
 *   columns=3 → grille 3 colonnes (image-03 source)
 *
 * Validation : une LogicException est levée si 'alt' est absent ou vide.
 */
#[AsTwigComponent('tsf:Ui:GridImage', template: '@Tailsfadmin/components/Ui/GridImage.html.twig')]
final class GridImage
{
    /** @var array<int, array{src: string, alt: string, caption?: string}> */
    public array $images = [];

    /** 1|2|3 */
    public int $columns = 1;

    /**
     * Valide que chaque image possède un attribut alt non vide.
     *
     * @throws \LogicException
     */
    public function mount(): void
    {
        foreach ($this->images as $index => $image) {
            if (empty($image['alt'])) {
                throw new \LogicException(
                    \sprintf(
                        'Chaque image doit posséder un attribut alt non vide (accessibilité). Image index %d.',
                        $index,
                    ),
                );
            }
        }
    }
}
