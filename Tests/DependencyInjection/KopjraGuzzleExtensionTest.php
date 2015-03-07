<?php

namespace Kopjra\GuzzleBundle\Tests\DependencyInjection;

use Kopjra\GuzzleBundle\Tests\AppKernel;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Kopjra\GuzzleBundle\DependencyInjection\KopjraGuzzleExtension
 */
class KopjraGuzzleExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\HttpKernel\Kernel
     */
    private $kernel;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->kernel = new AppKernel('KopjraGuzzleExtensionTest', true);
        $this->kernel->boot();
    }

    /**
     * @covers ::load
     */
    public function testService()
    {
        $container = $this->kernel->getContainer();

        $this->assertTrue($container->has('kpj_guzzle'));
        $this->assertInstanceOf('GuzzleHttp\\ClientInterface', $container->get('kpj_guzzle'));
    }
}
