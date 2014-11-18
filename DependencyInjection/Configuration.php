<?php

namespace Bluetea\CrowdAuthenticationBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bluetea_crowd_authentication');

        $rootNode
            ->children()
                ->scalarNode('base_url')->end()
                ->scalarNode('application')->end()
                ->scalarNode('password')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
