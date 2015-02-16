<?php

namespace Kopjra\GuzzleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * {@inheritdoc}
 */
class SubscribersCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $emitter = $container->getDefinition('kpj_guzzle.event.emitter');
        $subscribers = $container->findTaggedServiceIds('kpj_guzzle.subscriber');

        $static = array_filter($subscribers, function ($attrs) {
            return isset($attrs['static']) && $attrs['static'];
        });
        foreach ($static as $subscriber => $attrs) {
            $this->addStatic($container, $subscriber, $attrs);
        }

        $dynamic = array_diff($subscribers, $static);
        foreach (array_keys($dynamic) as $subscriber) {
            $emitter->addMethodCall('attach', [
                new Reference($subscriber),
            ]);
        }
    }

    /**
     * Adds an alternative system to load providers (ex: CacheSubscriber).
     *
     * @param ContainerBuilder $container
     * @param string           $subscriber
     * @param array            $attrs
     */
    private function addStatic(ContainerBuilder $container, $subscriber, array $attrs = array())
    {
        $definition = $container->getDefinition($subscriber);

        if (!isset($attrs['class'])) {
            $attrs['class'] = $definition->getClass();
        }

        if (!isset($attrs['method'])) {
            $attrs['method'] = 'attach';
        }

        $definition->setFactoryClass($attrs['class']);
        $definition->setFactoryMethod($attrs['method']);

        // Guzzle client must be the first argument ...

        $arguments = $definition->getArguments();

        // ... without removing the other arguments
        array_unshift($arguments, new Reference('kpj_guzzle'));

        $definition->setArguments($arguments);
    }
}
