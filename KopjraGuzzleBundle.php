<?php

namespace Kopjra\GuzzleBundle;

use Kopjra\GuzzleBundle\DependencyInjection\Compiler\ServicesCompilerPass;
use Kopjra\GuzzleBundle\DependencyInjection\Compiler\SubscribersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * {@inheritdoc}
 */
class KopjraGuzzleBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SubscribersCompilerPass());
        $container->addCompilerPass(new ServicesCompilerPass());
    }
}
