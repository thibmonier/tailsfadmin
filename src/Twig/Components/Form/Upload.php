<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Composant Upload (Dropzone) — tsf:Form:Upload.
 *
 * US-016 : zone drag&drop stylée TailAdmin câblée au contrôleur Stimulus
 * "tailsfadmin--dropzone" (Dropzone v6 vendoré via importmap).
 * Aucun Alpine.js. Aucun CDN.
 *
 * Options transmises via data-values au contrôleur :
 *   - url           : endpoint POST d'upload (obligatoire)
 *   - maxFiles      : nombre max de fichiers (défaut 10)
 *   - maxFilesize   : taille max par fichier en Mo (défaut 5)
 *   - acceptedFiles : types MIME/extensions acceptés, ex. "image/*,.pdf" (défaut "" = tous)
 *   - paramName     : nom du paramètre HTTP du fichier (défaut "file")
 *
 * Utilisation :
 *   <twig:tsf:Form:Upload url="/api/upload" />
 *   <twig:tsf:Form:Upload
 *       url="/api/upload"
 *       label="Pièces jointes"
 *       acceptedFiles="image/*,.pdf"
 *       :maxFiles="3"
 *       :maxFilesize="10"
 *   />
 */
#[AsTwigComponent('tsf:Form:Upload', template: '@Tailsfadmin/components/Form/Upload.html.twig')]
final class Upload
{
    /** Libellé affiché au-dessus de la zone. */
    public string $label = '';

    /** Endpoint POST d'upload (transmis au contrôleur). */
    public string $url = '/upload';

    /** Nombre maximum de fichiers autorisés. */
    public int $maxFiles = 10;

    /** Taille maximale par fichier en Mo. */
    public int $maxFilesize = 5;

    /**
     * Types MIME ou extensions acceptés.
     * Vide = tous les types.
     * Ex. : "image/*,.pdf"
     */
    public string $acceptedFiles = '';

    /** Nom du paramètre HTTP transmis avec le fichier. */
    public string $paramName = 'file';

    /** Texte affiché dans la zone (ligne 1). */
    public string $hint = 'Glissez-déposez vos fichiers ici';

    /** Texte secondaire (ligne 2). */
    public string $subHint = 'ou cliquez pour sélectionner';
}
