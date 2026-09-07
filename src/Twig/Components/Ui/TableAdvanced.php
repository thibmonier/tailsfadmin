<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Table avancée — tsf:Ui:TableAdvanced.
 *
 * US-017 : Table avec sélection (checkbox .tableCheckbox),
 * indicateurs de tri, dropdown d'actions (data-controller="tailsfadmin--dropdown"),
 * et cellules riches (Badge + Avatar).
 *
 * Réutilise le contrôleur Stimulus existant "tailsfadmin--dropdown" (US-007/012).
 * Aucun Alpine.js, aucun nouveau contrôleur JS.
 *
 * Structure de chaque ligne :
 *   [
 *     'name'    => string,   // nom de l'utilisateur
 *     'role'    => string,   // rôle/poste
 *     'project' => string,   // nom du projet
 *     'status'  => string,   // statut : 'active' | 'pending' | 'cancel'
 *     'budget'  => string,   // montant (ex. "2.8K")
 *   ]
 *
 * Utilisation :
 *   <twig:tsf:Ui:TableAdvanced title="Projets" :rows="[...]" />
 */
#[AsTwigComponent('tsf:Ui:TableAdvanced', template: '@Tailsfadmin/components/Ui/TableAdvanced.html.twig')]
final class TableAdvanced
{
    /** Titre optionnel affiché au-dessus de la table. */
    public string $title = 'Projets';

    /**
     * Lignes de la table avancée.
     *
     * @var array<int, array{name: string, role: string, project: string, status: string, budget: string}>
     */
    public array $rows = [];
}
