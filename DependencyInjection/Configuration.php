<?php

namespace Kopjra\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kopjra_guzzle');

        $rootNode
            ->children()
                ->variableNode('client')
                    ->info('Guzzle 5 client configuration (http://docs.guzzlephp.org/en/latest/clients.html)')
                ->end()
                ->arrayNode('subscribers')
                    ->children()
                        ->append($this->addCacheSubscriberNode())
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Add a configuration for the cache subscriber.
     *
     * @link https://github.com/guzzle/cache-subscriber
     *
     * @return TreeBuilder
     */
    private function addCacheSubscriberNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cache');

        $rootNode
            ->addDefaultsIfNotSet()
            ->treatFalseLike(['enabled' => false])
            ->treatNullLike(['enabled' => false])
            ->children()
                ->booleanNode('enabled')
                    ->defaultFalse()
                ->end()
                ->scalarNode('provider')
                    ->cannotBeEmpty()
                    ->defaultValue('%kopjra_guzzle.subscribers.cache.provider%')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
