<?php

/**
 * Manifeste des dépendances JS du bundle tailsfadmin — SOURCE DE VÉRITÉ.
 *
 * Ces entrées sont les bibliothèques tierces dont dépendent les contrôleurs
 * Stimulus du bundle (ApexCharts, jsvectormap, flatpickr, Dropzone, FullCalendar).
 * La commande `tailsfadmin:assets:install` les ajoute à l'`importmap.php` de
 * l'application hôte et déclenche leur vendoring local (aucun CDN au runtime —
 * ADR-004/006). Voir ADR-007 pour le mécanisme et la preuve du spike.
 *
 * NE PAS y mettre les entrées propres à l'hôte (`app`, `@hotwired/stimulus`,
 * `@symfony/stimulus-bundle`) : elles proviennent du squelette / de la recette
 * StimulusBundle de l'application, pas du bundle.
 *
 * Schéma d'une entrée (aligné sur le format natif d'importmap.php) :
 *
 * @return array<string, array{
 *     version?: string,            // pin distant (téléchargé puis vendoré)
 *     package_specifier?: string,  // "package/chemin" distant, défaut = clé
 *     local?: string,              // chemin LOGIQUE AssetMapper d'un fichier livré
 *                                  //   par le bundle (piné en `path:` côté hôte) —
 *                                  //   ex. shim ESM sous bundles/tailsfadmin-vendor/
 *     type?: 'js'|'css'|'json',    // défaut 'js'
 * }>
 */

return [
    // flatpickr (contrôleur datepicker)
    'flatpickr' => ['version' => '4.6.13'],
    'flatpickr/dist/flatpickr.min.css' => ['version' => '4.6.13', 'type' => 'css'],

    // Dropzone (contrôleur dropzone)
    'dropzone' => ['version' => '6.2.0'],
    'dropzone/dist/dropzone.css' => ['version' => '6.2.0', 'type' => 'css'],

    // ApexCharts (contrôleur apexcharts)
    'apexcharts' => ['version' => '7.1.0'],
    'apexcharts/core' => ['version' => '7.1.0'],

    // FullCalendar (contrôleur calendar) — le build global distant + un shim ESM
    // LIVRÉ PAR LE BUNDLE qui réexporte l'API ESM (résolution v6/v7, cf. ADR-006).
    // Source : assets/vendor-src/fullcalendar/fullcalendar-esm.js, exposée par
    // prepend() sous le namespace AssetMapper "bundles/tailsfadmin-vendor".
    'fullcalendar/index.global.min.js' => ['version' => '6.1.21'],
    'fullcalendar' => ['local' => 'bundles/tailsfadmin-vendor/fullcalendar/fullcalendar-esm.js'],

    // jsvectormap (contrôleur vectormap) — lib + carte du monde + CSS
    'jsvectormap' => ['version' => '1.7.0'],
    'jsvectormap/dist/maps/world.js' => ['version' => '1.7.0'],
    'jsvectormap/dist/jsvectormap.min.css' => ['version' => '1.7.0', 'type' => 'css'],
];
