<?php

namespace Kopjra\GuzzleBundle\Tests;

use Kopjra\GuzzleBundle\DependencyInjection\Configuration;
use PHPUnit_Framework_TestCase;

/**
 * Class ConfigurationTest.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\Tests
 */
class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    /**
     * [$configuration description]
     *
     * @var [type]
     */
    protected $configuration;

    /**
     * [testConfiguration description]
     *
     * @return [type] [description]
     */
    public function testConfiguration()
    {
        $this->configuration = new Configuration();

        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\Builder\TreeBuilder',
            $this->configuration->getConfigTreeBuilder()
        );
    }
}
