<?php
/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 03/01/15
 */

use Symfony\Cmf\Component\Testing\HttpKernel\TestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 */
class AppKernel extends TestKernel {
    public function configure() {
        $this->requireBundleSets( [ 'default' ] );

        $this->addBundles([
            new \Kopjra\GuzzleBundle\KopjraGuzzleBundle(),
        ]);
    }

    /**
     * Loads the container configuration.
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     *
     * @api
     */
    public function registerContainerConfiguration(LoaderInterface $loader){
        $loader->load(__DIR__.'/config/config.php');
    }
}