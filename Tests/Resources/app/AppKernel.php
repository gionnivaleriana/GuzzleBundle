<?php

namespace Kopjra\GuzzleBundle\Tests\Resources\app;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernel
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 03/01/15
 */
class AppKernel extends Kernel {

    public function registerBundles() {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Kopjra\GuzzleBundle\KopjraGuzzleBundle()
        ];

        return $bundles;
    }

    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader){
        $loader->load( __DIR__ . '/config/config.yml' );
    }
}