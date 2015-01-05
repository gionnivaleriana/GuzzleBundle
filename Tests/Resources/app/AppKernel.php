<?php
/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 03/01/15
 */

use Symfony\Cmf\Component\Testing\HttpKernel\TestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends TestKernel
{
    public function configure()
    {
        $this->requireBundleSets(array(
            'default',
        ));

        $this->addBundles(array(
            new \Kopjra\GuzzleBundle\KopjraGuzzleBundle(),
        ));
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.php');
    }
}
