<?php

declare(strict_types=1);

/**
 * Configuration PHP CS Fixer — tailsfadmin bundle.
 *
 * Règles de base : @PSR12
 * Cibles : src/ et tests/
 */

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notPath('vendor');

return (new PhpCsFixer\Config())
    // Autorise les fixers « risky » (ex. declare_strict_types)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        // Déclarations strictes obligatoires
        'declare_strict_types' => true,
        // Ordre des imports
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        // Espacement cohérent
        'array_syntax' => ['syntax' => 'short'],
        'trailing_comma_in_multiline' => true,
        // Commentaires
        'phpdoc_align' => false,
        'phpdoc_trim' => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
