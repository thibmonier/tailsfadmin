<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Calendrier — tsf:Calendar.
 *
 * US-020 : calendrier interactif FullCalendar v6 câblé au contrôleur Stimulus
 * "tailsfadmin--calendar" (vendoré via importmap). Aucun Alpine.js. Aucun CDN.
 *
 * La modale d'événement réutilise le composant tsf:Ui:Modal (US-011) + contrôleur
 * tailsfadmin--modal sans modification. La communication passe par
 * application.getControllerForElementAndIdentifier() dans le contrôleur calendar.
 *
 * Les événements sont sérialisés en JSON et transmis via data-*-value.
 *
 * Utilisation :
 *   <twig:tsf:Calendar />
 *   <twig:tsf:Calendar
 *       title="Planning mensuel"
 *       initialView="dayGridMonth"
 *       :events="[
 *           {title:'Réunion',start:'2026-09-10'},
 *           {title:'Conf.',start:'2026-09-15',end:'2026-09-17'},
 *       ]"
 *   />
 */
#[AsTwigComponent('tsf:Calendar', template: '@Tailsfadmin/components/Calendar.html.twig')]
final class Calendar
{
    /**
     * Tableau d'événements FullCalendar.
     * Chaque événement = ['title' => string, 'start' => string (ISO), 'end' => string (optionnel), ...].
     *
     * @var array<int, array{title: string, start: string, end?: string, color?: string}>
     */
    public array $events = [];

    /**
     * Vue initiale du calendrier.
     * Valeurs : "dayGridMonth" | "timeGridWeek" | "listWeek".
     */
    public string $initialView = 'dayGridMonth';

    /** Titre optionnel affiché au-dessus du calendrier. */
    public string $title = '';
}
