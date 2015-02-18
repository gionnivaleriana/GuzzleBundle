<?php

namespace Kopjra\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KopjraGuzzleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        // Twig extension is loaded only if it's enabled
        // If twig isn't available then this extension isn't loaded
        if ($config['twig']['enabled'] && $container->hasDefinition('twig')) {
            $loader->load('twig.xml');
        }

        // Load subscribers sections
        $this->loadSubscribers($config['subscribers'], $container);

        // Replace the emitter with a new one because
        // the framework doesn't allow calls on getters
        $guzzle = $container->getDefinition('kpj_guzzle');

        $config['client']['emitter'] = new Reference('kpj_guzzle.event.emitter');

        $guzzle->replaceArgument(0, $config['client']);
    }

    /**
     * For each subscriber, if enabled, load services and parameters.
     *
     * @param array            $config    Subscribers section only.
     * @param ContainerBuilder $container Container builder.
     */
    private function loadSubscribers(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config/subscribers')
        );

        // Cache is loaded only if it's enabled
        if ($config['cache']['enabled']) {
            $this->createCacheDefaultStorage($container);

            $loader->load('cache.xml');
        }

        // Logger is loaded only if it's enabled
        // If monolog isn't available then logger isn't loaded
        // http://slides.seld.be/?file=2011-10-20+High+Performance+Websites+with+Symfony2.html#33
        if ($config['log']['enabled'] && $container->hasDefinition('logger')) {
            $loader->load('log.xml');
        }

        // OAuth is loaded only if it's enabled
        if ($config['oauth']['enabled']) {
            $loader->load('oauth.xml');

            $this->loadOAuthConfiguration($config['oauth'], $container);
        }

        // Retry system is loaded only if it's enabled
        if ($config['retry']['enabled']) {
            $loader->load('retry.xml');

            $this->loadRetryConfiguration($config['retry'], $container);
        }
    }

    /**
     * Create a default storage if no one is provided (that ID is required).
     *
     * @param ContainerBuilder $container Container builder.
     */
    private function createCacheDefaultStorage(ContainerBuilder $container)
    {
        if (!$container->has('kpj_guzzle.subscribers.cache.storage')) {
            $class = $container->getParameter('kpj_guzzle.subscribers.cache.storage.class');
            $container->register('kpj_guzzle.subscribers.cache.storage', $class);
        }
    }

    /**
     * Loads different OAuth configurations.
     *
     * @param array            $config    OAuth section only.
     * @param ContainerBuilder $container Container builder.
     */
    private function loadOAuthConfiguration(array $config, ContainerBuilder $container)
    {
        // required configurations
        $container->setParameter('kpj_guzzle.subscribers.oauth.consumer_key', $config['consumer_key']);
        $container->setParameter('kpj_guzzle.subscribers.oauth.consumer_secret', $config['consumer_secret']);
        $container->setParameter('kpj_guzzle.subscribers.oauth.oauth_version', $config['version']);
        $container->setParameter('kpj_guzzle.subscribers.oauth.request_method', $config['request_method']);
        $container->setParameter('kpj_guzzle.subscribers.oauth.signature_method', $config['signature_method']);

        // optional configurations
        if ($config['callback']) {
            $container->setParameter('kpj_guzzle.subscribers.oauth.callback', $config['callback']);
        }

        if ($config['realm']) {
            $container->setParameter('kpj_guzzle.subscribers.oauth.realm', $config['realm']);
        }

        if ($config['token']) {
            $container->setParameter('kpj_guzzle.subscribers.oauth.token', $config['token']);
        }

        if ($config['token_secret']) {
            $container->setParameter('kpj_guzzle.subscribers.oauth.token_secret', $config['token_secret']);
        }

        if ($config['verifier']) {
            $container->setParameter('kpj_guzzle.subscribers.oauth.oauth_verifier', $config['verifier']);
        }
    }

    /**
     * Loads different retry configurations.
     *
     * @param array            $config    Retry section only.
     * @param ContainerBuilder $container Container builder.
     */
    private function loadRetryConfiguration(array $config, ContainerBuilder $container)
    {
        $container->setParameter('kpj_guzzle.subscribers.retry.delay', $config['delay']);
        $container->setParameter('kpj_guzzle.subscribers.retry.filter.class', $config['filter']['class']);
        $container->setParameter('kpj_guzzle.subscribers.retry.filter.method', $config['filter']['method']);
        $container->setParameter('kpj_guzzle.subscribers.retry.max', $config['max']);
    }
}
