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
     * Default subscribers (all disabled) of them
     * aren't explicitly defined.
     *
     * @var array
     */
    private $defaultSubscribers = [
        'cache' => false,
        'log' => false,
        'oauth' => false,
        'retry' => false,
    ];

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kpj_guzzle');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->variableNode('client')
                    ->defaultValue([])
                    ->info('Guzzle 5 client configuration (http://docs.guzzlephp.org/en/latest/clients.html)')
                ->end()
                ->arrayNode('subscribers')
                    ->defaultValue($this->defaultSubscribers)
                    ->prototype('array')->end()
                    ->treatNullLike($this->defaultSubscribers)
                    ->children()
                        ->append($this->addCacheSubscriberNode())
                        ->append($this->addLogSubscriberNode())
                        ->append($this->addOAuthSubscriberNode())
                        ->append($this->addRetrySubscriberNode())
                    ->end()
                ->end()
                ->append($this->addTwigExtensionNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Add a configuration for the oauth subscriber.
     *
     * @link https://github.com/guzzle/oauth-subscriber
     *
     * @return TreeBuilder
     */
    private function addOAuthSubscriberNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('oauth');

        $rootNode
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->info('https://github.com/guzzle/oauth-subscriber')
            ->children()
                ->enumNode('request_method')
                    ->defaultValue('header')
                    ->values(['header', 'query'])
                ->end()
                ->enumNode('signature_method')
                    ->defaultValue('HMAC-SHA1')
                    ->values(['HMAC-SHA1', 'RSA-SHA1', 'PLAINTEXT'])
                ->end()
                ->scalarNode('callback')->end()
                ->scalarNode('consumer_key')
                    ->cannotBeEmpty()
                    ->defaultValue('anonymous')
                ->end()
                ->scalarNode('consumer_secret')
                    ->cannotBeEmpty()
                    ->defaultValue('anonymous')
                ->end()
                ->scalarNode('realm')->end()
                ->scalarNode('token')->end()
                ->scalarNode('token_secret')->end()
                ->scalarNode('verifier')->end()
                ->scalarNode('version')
                    ->cannotBeEmpty()
                    ->defaultValue('1.0')
                ->end()
            ->end()
        ;

        return $rootNode;
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
            ->canBeEnabled()
            ->info('https://github.com/guzzle/cache-subscriber')
            ->children()
                ->scalarNode('provider')
                    ->cannotBeEmpty()
                    ->defaultValue('GuzzleHttp\Subscriber\Cache\CacheSubscriber')
                ->end()
            ->end()
        ;

        return $rootNode;
    }

    /**
     * Add a configuration for the retry subscriber.
     *
     * @link https://github.com/guzzle/retry-subscriber
     *
     * @return TreeBuilder
     */
    private function addRetrySubscriberNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('retry');

        $rootNode
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->info('https://github.com/guzzle/retry-subscriber')
            ->children()
                ->integerNode('delay')
                    ->cannotBeEmpty()
                    ->defaultValue(1000)
                    ->min(0)
                ->end()
                ->arrayNode('filter')
                    ->children()
                        ->scalarNode('class')
                            ->cannotBeEmpty()
                            ->defaultValue('GuzzleHttp\Subscriber\Retry\RetrySubscriber')
                        ->end()
                        ->scalarNode('method')
                            ->cannotBeEmpty()
                            ->defaultValue('createStatusFilter')
                        ->end()
                    ->end()
                ->end()
                ->integerNode('max')
                    ->cannotBeEmpty()
                    ->defaultValue(5)
                    ->min(1)
                ->end()
            ->end()
        ;

        return $rootNode;
    }

    /**
     * Add a configuration for the log subscriber.
     *
     * @link https://github.com/guzzle/log-subscriber
     *
     * @return TreeBuilder
     */
    private function addLogSubscriberNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('log');

        $rootNode
            ->canBeEnabled()
            ->info('https://github.com/guzzle/log-subscriber')
        ;

        return $rootNode;
    }

    /**
     * Add a configuration for the Twig extension.
     *
     * @return TreeBuilder
     */
    public function addTwigExtensionNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('twig');

        $rootNode
            ->canBeEnabled()
        ;

        return $rootNode;
    }
}
