<?php

namespace Kopjra\GuzzleBundle\Tests;

use Kopjra\GuzzleBundle\Services\Services;
use Kopjra\GuzzleBundle\Tests\Resources\app\AppKernel;
use PHPUnit_Framework_TestCase;

/**
 * Class ServicesTest.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\Tests
 */
class ServicesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Services
     */
    protected $services;

    /**
     * [setUp description]
     */
    public function setUp()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $container      = $kernel->getContainer();
        $this->Services = $container->get( 'kopjra.guzzle.services' );
    }

    public function testSetWebServices() {
        $this->assertTrue( is_object( $this->Services->setWebServices( [ "TestWebservice" ] ) ) );
    }
}
