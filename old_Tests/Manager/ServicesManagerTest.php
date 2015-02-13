<?php

namespace Kopjra\GuzzleBundle\Tests;

use Kopjra\GuzzleBundle\Manager\ServicesManager;
use Kopjra\GuzzleBundle\Tests\Resources\app\AppKernel;
use PHPUnit_Framework_TestCase;

/**
 * Class ServicesTest.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\Tests
 */
class ServicesManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Services
     */
    protected $Services;


    public function setUp()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $container      = $kernel->getContainer();
        $this->Services = $container->get( 'kopjra.guzzle.services' );
    }

    public function testSet()
    {
        $webService = $this->Services->set(["TestWebservice"]);
        $this->assertTrue(is_object($webService));
    }
}
