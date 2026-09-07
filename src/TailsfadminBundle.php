<?php

declare(strict_types=1);

namespace Tailsfadmin;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Bundle principal tailsfadmin.
 *
 * AbstractBundle (Symfony 6.1+) agit comme sa propre Extension.
 * La configuration est déclarée dans configure(), traitée par le framework,
 * et reçue déjà normalisée dans loadExtension().
 *
 * Auto-configure via prepend() :
 *   - Namespace Twig "@Tailsfadmin" → templates/ du bundle
 *   - Préfixe Twig Components "tsf" pour Tailsfadmin\Twig\Components\
 *   - AssetMapper : expose assets/controllers/ sous "bundles/tailsfadmin/"
 *
 * Via loadExtension() :
 *   - Charge services.yaml (BundleInfo, MenuBuilder, TailsfadminExtension…)
 *   - Passe la config menu au paramètre tailsfadmin.menu pour injection dans MenuBuilder
 */
final class TailsfadminBundle extends AbstractBundle implements PrependExtensionInterface
{
    /**
     * Arbre de configuration du bundle.
     *
     * Utilisé par Symfony pour valider et normaliser tailsfadmin.yaml.
     * Pattern AbstractBundle (Symfony 6.1+) : configure() + loadExtension().
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('menu')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('group')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('items')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('label')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('path')->defaultValue('#')->end()
                                        ->scalarNode('icon')->defaultValue('')->end()
                                        ->arrayNode('children')
                                            ->arrayPrototype()
                                                ->children()
                                                    ->scalarNode('label')->isRequired()->cannotBeEmpty()->end()
                                                    ->scalarNode('path')->defaultValue('#')->end()
                                                    ->scalarNode('icon')->defaultValue('')->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /** @param array<string, mixed> $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader(
            $builder,
            new FileLocator($this->getPath() . '/config')
        );
        $loader->load('services.yaml');

        // Passe la config menu au paramètre DI pour injection dans MenuBuilder
        /** @var list<array<string, mixed>> $menu */
        $menu = $config['menu'] ?? [];
        $builder->setParameter('tailsfadmin.menu', $menu);
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
