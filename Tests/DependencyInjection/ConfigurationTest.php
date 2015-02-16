<?php

namespace Kopjra\GuzzleBundle\Tests\DependencyInjection\Configuration;

use Kopjra\GuzzleBundle\DependencyInjection\Configuration;
use PHPUnit_Framework_TestCase;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @coversDefaultClass \Kopjra\GuzzleBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $config;

    public function setUp()
    {
        $this->config = new Configuration();
    }

    public function testGetConfigTreeBuilder()
    {
        $this->assertInstanceOf(
            'Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder',
            $this->config->getConfigTreeBuilder()
        );
    }
}
