<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Alert — tsf:Ui:Alert.
 *
 * US-008 : 4 variantes (success, info, warning, error) + mode dismissible
 * via le contrôleur Stimulus "tailsfadmin--alert-dismiss".
 *
 * Toutes les entrées sont échappées par Twig (pas de |raw sur le titre/message).
 *
 * Utilisation :
 *   <twig:tsf:Ui:Alert type="success" title="Succès" message="Opération réussie." />
 *   <twig:tsf:Ui:Alert type="error" title="Erreur" message="..." dismissible />
 */
#[AsTwigComponent('tsf:Ui:Alert', template: '@Tailsfadmin/components/Ui/Alert.html.twig')]
final class Alert
{
    /** Variante visuelle : success | info | warning | error */
    public string $type = 'info';

    /** Titre affiché en gras au-dessus du message. */
    public string $title = '';

    /** Corps du message textuel. */
    public string $message = '';

    /**
     * Si true, affiche un bouton de fermeture câblé au contrôleur
     * "tailsfadmin--alert-dismiss".
     */
    public bool $dismissible = false;
}
