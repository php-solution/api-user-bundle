<?php

namespace PhpSolution\ApiUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('api_user');
        $rootNode
            ->children()
                ->scalarNode('user_entity_class')->isRequired()->end()
                ->scalarNode('user_enabled_by_default')->defaultFalse()->end()
                ->scalarNode('send_email_confirmation')->defaultTrue()->end()
                ->scalarNode('send_forgot_password')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
}