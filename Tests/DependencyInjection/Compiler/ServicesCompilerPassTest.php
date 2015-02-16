<?php

namespace Kopjra\GuzzleBundle\Tests\DependencyInjection\Compiler;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Subscriber\Mock;
use Kopjra\GuzzleBundle\DependencyInjection\Compiler\ServicesCompilerPass;
use Kopjra\GuzzleBundle\DependencyInjection\KopjraGuzzleExtension;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @coversDefaultClass \Kopjra\GuzzleBundle\DependencyInjection\Compiler\ServicesCompilerPass
 */
class ServicesCompilerPassTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $extension = new KopjraGuzzleExtension();

        $this->container->registerExtension($extension);
        $this->container->loadFromExtension($extension->getAlias());
    }

    /**
     * @covers ::process
     */
    public function testProcess()
    {
        $container = $this->container;

        $provider = new Definition(
            'GuzzleHttp\\Subscriber\\Mock',
            [
                [
                    new Response(200),
                    new Response(202),
                ],
            ]
        );
        $provider->addTag('kpj_guzzle.subscriber');

        $container->setDefinition('acme.guzzle.custom_subscriber', $provider);

        $container->addCompilerPass(new ServicesCompilerPass());
        $container->compile();

        $this->assertTrue($container->has('kpj_guzzle'));

        $this->markTestIncomplete('Missing test service');
    }
}
