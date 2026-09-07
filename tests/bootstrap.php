<?php

declare(strict_types=1);

/**
 * Bootstrap PHPUnit pour le bundle tailsfadmin.
 *
 * Les tests du bundle (Unit + Functional) n'ont pas besoin d'un kernel Symfony
 * complet — l'autoloader Composer suffit pour les tests unitaires.
 * Les tests fonctionnels de la démo vivent dans demo/tests/ avec le kernel de la démo.
 */

require dirname(__DIR__) . '/vendor/autoload.php';
