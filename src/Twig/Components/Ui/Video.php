<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Video — tsf:Ui:Video
 * US-013 : embed iframe responsive (YouTube / Vimeo) avec ratio configurable.
 *
 * Props :
 *   src   (string) — URL de l'embed (HTTPS obligatoire)
 *   title (string) — Libellé aria de l'iframe (accessibilité)
 *   ratio (string) — Ratio d'affichage : 16/9|4/3|21/9|1/1 (défaut 16/9)
 *
 * Sécurité : l'URL src est validée en HTTPS lors du montage.
 * Accessibilité : title obligatoire sur l'iframe.
 *
 * @throws \LogicException si l'URL n'est pas en HTTPS
 */
#[AsTwigComponent('tsf:Ui:Video', template: '@Tailsfadmin/components/Ui/Video.html.twig')]
final class Video
{
    public string $src = '';

    public string $title = 'Vidéo';

    /** 16/9|4/3|21/9|1/1 */
    public string $ratio = '16/9';

    /**
     * Valide que l'URL de l'embed utilise HTTPS.
     *
     * @throws \LogicException
     */
    public function mount(): void
    {
        if ($this->src !== '' && !str_starts_with($this->src, 'https://')) {
            throw new \LogicException('L\'URL de l\'embed vidéo doit utiliser HTTPS.');
        }
    }
}
