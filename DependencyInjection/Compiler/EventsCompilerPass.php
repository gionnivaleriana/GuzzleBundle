<?php

namespace Kopjra\GuzzleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * {@inheritdoc}
 */
class EventsCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $guzzle = $container->getDefinition('guzzle');
        $emitter = $container->getDefinition('kopjra.guzzle_bundle.event_listener.emitter');

        $subscribers = $container->findTaggedServiceIds( 'guzzle.subscriber' );

        foreach ($subscribers as $id => $attributes) {
            $emitter->addMethodCall('attach', [
                new Reference($id),
            ]);
        }

        $config = (array) $container->getExtensionConfig('kopjra_guzzle');
        $config = $config[0];
        $config['emitter'] = new Reference('kopjra.guzzle_bundle.event_listener.emitter');

        $guzzle->replaceArgument(0, $config);
    }
}
