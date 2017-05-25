<?php

namespace Tigreboite\FunkylabBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
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
        $rootNode = $treeBuilder->root('tigreboite_funkylab');

        $theme = array('yellow', 'yellow-light', 'green', 'green-light', 'red', 'red-light');

        $rootNode
          ->addDefaultsIfNotSet()
          ->children()
              ->arrayNode('default_menu')
                  ->addDefaultsIfNotSet()
                  ->children()
                      ->booleanNode('blog')
                        ->defaultTrue()
                      ->end()
                      ->booleanNode('page')
                        ->defaultTrue()
                      ->end()
                      ->booleanNode('user')
                        ->defaultTrue()
                      ->end()
                      ->booleanNode('language')
                        ->defaultTrue()
                      ->end()
                      ->booleanNode('country')
                        ->defaultTrue()
                      ->end()
                      ->booleanNode('translator')
                        ->defaultTrue()
                      ->end()
                      ->booleanNode('activity')
                        ->defaultTrue()
                      ->end()
                  ->end()
              ->end()
          ->end()
        ;

        return $treeBuilder;
    }
}
