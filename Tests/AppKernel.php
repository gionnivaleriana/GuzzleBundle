<?php

namespace Kopjra\GuzzleBundle\Tests;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernel.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 */
class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new \Kopjra\GuzzleBundle\KopjraGuzzleBundle(),
        ];

        return $bundles;
    }

    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $config = __DIR__.'/Resources/config/config_'.$this->getEnvironment().'.yml';

        if (file_exists($config)) {
            $loader->load($config);

            return;
        }

        $loader->load(__DIR__.'/Resources/config/config.yml');
    }
}
