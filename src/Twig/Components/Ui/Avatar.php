<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Avatar — tsf:Ui:Avatar.
 *
 * US-009 : 6 tailles + point de statut (online/offline) + fallback initiales.
 *
 * Si `src` est vide, le composant affiche les initiales extraites de `alt`.
 * Toutes les entrées sont auto-échappées par Twig (pas de |raw).
 *
 * Utilisation :
 *   <twig:tsf:Ui:Avatar src="/img/user.jpg" alt="Alice Dupont" size="md" />
 *   <twig:tsf:Ui:Avatar alt="Bob Martin" size="lg" status="online" />
 */
#[AsTwigComponent('tsf:Ui:Avatar', template: '@Tailsfadmin/components/Ui/Avatar.html.twig')]
final class Avatar
{
    /** URL de l'image. Vide → fallback initiales. */
    public string $src = '';

    /** Texte alternatif (aussi utilisé pour les initiales si src est vide). */
    public string $alt = '';

    /**
     * Taille de l'avatar.
     * xs | sm | md | lg | xl | 2xl
     */
    public string $size = 'md';

    /**
     * Point de statut.
     * '' (aucun) | 'online' | 'offline'
     */
    public string $status = '';

    /**
     * Calcule les initiales (1 ou 2 lettres) depuis la prop alt.
     * Exposé publiquement pour Twig.
     */
    public function initials(): string
    {
        $trimmed = trim($this->alt);
        if ('' === $trimmed) {
            return '?';
        }
        $words = preg_split('/\s+/', $trimmed);
        if (!\is_array($words)) {
            return mb_strtoupper(mb_substr($trimmed, 0, 1));
        }
        $first = mb_strtoupper(mb_substr($words[0], 0, 1));
        $second = isset($words[1]) ? mb_strtoupper(mb_substr($words[1], 0, 1)) : '';

        return $first.$second;
    }
}
