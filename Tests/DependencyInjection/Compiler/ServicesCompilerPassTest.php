<?php

namespace Kopjra\GuzzleBundle\Tests\DependencyInjection\Compiler;

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

    private $description = [
        'baseUrl' => 'http://httpbin.org/',
        'operations' => [
            'testing' => [
                'httpMethod' => 'GET',
                'uri' => '/get/{foo}',
                'responseModel' => 'getResponse',
                'parameters' => [
                    'foo' => [
                        'type' => 'string',
                        'location' => 'uri',
                    ],
                    'bar' => [
                        'type' => 'string',
                        'location' => 'query',
                    ],
                ],
            ],
        ],
        'models' => [
            'getResponse' => [
                'type' => 'object',
                'additionalProperties' => [
                    'location' => 'json',
                ],
            ],
        ],
    ];

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
            'GuzzleHttp\Command\Guzzle\Description',
            [
                $this->description,
            ]
        );
        $provider->addTag('kpj_guzzle.service.description');

        $container->setDefinition('acme.guzzle.service', $provider);

        $container->addCompilerPass(new ServicesCompilerPass());
        $container->compile();

        $this->assertTrue($container->has('kpj_guzzle'));
        $this->assertTrue($container->has('kpj_guzzle.services.acme.guzzle.service'));
    }

    /**
     * @covers ::process
     */
    public function testProcessWithCustomName()
    {
        $container = $this->container;

        $provider = new Definition(
            'GuzzleHttp\Command\Guzzle\Description',
            [
                $this->description,
            ]
        );
        $provider->addTag(
            'kpj_guzzle.service.description',
            [
                'name' => 'httpbin',
            ]
        );

        $container->setDefinition('acme.guzzle.service.description', $provider);

        $container->addCompilerPass(new ServicesCompilerPass());
        $container->compile();

        $this->assertTrue($container->has('kpj_guzzle'));
        $this->assertTrue($container->has('kpj_guzzle.services.httpbin'));
    }
}
