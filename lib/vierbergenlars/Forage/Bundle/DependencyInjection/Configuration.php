<?php

namespace vierbergenlars\Forage\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vierbergenlars_forage');

        $rootNode->children()
                ->arrayNode('transport')->children()
                        ->enumNode('type')->values(array('http', 'socket'))->defaultValue('http')->info('Type of transport to use')->end()
                        ->scalarNode('location')->defaultValue('http//localhost:3000/')->info('Location of the Forage server')->end()
                ->end()->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
