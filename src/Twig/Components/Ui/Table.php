<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Table basique — tsf:Ui:Table.
 *
 * US-017 : Table de données avec en-têtes, lignes, wrapper responsive.
 * Wrapper overflow-x-auto pour éviter le débordement horizontal.
 * Dark mode, zébrures/hover fidèles à TailAdmin.
 *
 * Utilisation :
 *   <twig:tsf:Ui:Table
 *     title="Commandes récentes"
 *     :headers="['Produit', 'Catégorie', 'Prix', 'Statut']"
 *     :rows="[
 *       ['MacBook Pro', 'Électronique', '1 299 €', 'Livré'],
 *       ['iPhone 15', 'SmartPhone', '999 €', 'En attente'],
 *     ]"
 *   />
 */
#[AsTwigComponent('tsf:Ui:Table', template: '@Tailsfadmin/components/Ui/Table.html.twig')]
final class Table
{
    /** Titre optionnel affiché au-dessus de la table. */
    public string $title = '';

    /**
     * En-têtes de colonnes.
     *
     * @var string[]
     */
    public array $headers = [];

    /**
     * Données de la table : tableau de lignes, chaque ligne est un tableau de cellules (chaînes).
     *
     * @var array<int, array<int, string>>
     */
    public array $rows = [];

    /** Afficher les zébrures alternées (striped rows). */
    public bool $striped = false;
}
