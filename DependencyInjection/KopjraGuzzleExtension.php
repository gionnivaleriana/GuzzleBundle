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

        // For each subscriber, if enabled, load services and parameters
        foreach($config['subscribers'] as $subscriberName => $subscriber){
            if($subscriber['enabled']){
                $loader->load('subscribers/'.$subscriberName.'.xml');
                foreach ($config['subscribers'][$subscriberName] as $parameterName => $parameter) {
                    $container->setParameter('kopjra_guzzle.subscribers.'.$subscriberName.'.'.$parameterName, $parameter);
                }
            }
        }

        // If ServiceManager is enabled, load the service
        if($config['services_manager']['enabled']){
            $loader->load('manager/services.xml');
            foreach ($config['services_manager'] as $parameterName => $parameter) {
                $container->setParameter('kopjra_guzzle.services_manager.'.$parameterName, $parameter);
            }
        }
    }
}
