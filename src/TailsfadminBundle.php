<?php

declare(strict_types=1);

namespace Tailsfadmin;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Bundle principal tailsfadmin.
 *
 * Auto-configure via prepend() :
 *   - Namespace Twig "@Tailsfadmin" → templates/ du bundle
 *   - Préfixe Twig Components "tsf" pour Tailsfadmin\Twig\Components\
 *   - AssetMapper : expose assets/controllers/ sous "bundles/tailsfadmin/"
 */
final class TailsfadminBundle extends AbstractBundle implements PrependExtensionInterface
{
    /** @param array<string, mixed> $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader(
            $builder,
            new FileLocator($this->getPath() . '/config')
        );
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        // 1. Namespace Twig "@Tailsfadmin" → templates/ du bundle
        $container->prependExtensionConfig('twig', [
            'paths' => [$this->getPath() . '/templates' => 'Tailsfadmin'],
        ]);

        // 2. AssetMapper : expose assets/controllers/ sous "bundles/tailsfadmin/"
        //    Note : le préfixe "tsf" des composants Twig est déclaré via l'attribut
        //    #[AsTwigComponent('tsf:...')] dans chaque classe (plus fiable que prepend).
        //    Seuls les contrôleurs JS compilés sont exposés (pas les sources CSS Tailwind).
        if ($container->hasExtension('framework')) {
            $container->prependExtensionConfig('framework', [
                'asset_mapper' => [
                    'paths' => [
                        $this->getPath() . '/assets/controllers' => 'bundles/tailsfadmin',
                    ],
                ],
            ]);
        }
    }
}
