<?php

namespace Kopjra\GuzzleBundle\Tests;

use Kopjra\GuzzleBundle\Tests\Resources\app\AppKernel;
use Kopjra\GuzzleBundle\Services\Services;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 31/12/14
 *
 * Class ServicesTest
 * @package Kopjra\GuzzleBundle\Tests
 */
class ServicesTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Services
     */
    protected $Services;

    public function setUp() {
        $kernel = new AppKernel( 'test', true );
        $kernel->boot();
        $container      = $kernel->getContainer();
        $filesystem     = $container->get( 'knp_gaufrette.filesystem_map' );
        $this->Services = new Services( $filesystem );
    }

    public function testServices() {
        $this->assertInstanceOf( '\Kopjra\GuzzleBundle\Services\Services', $this->Services );
    }

}
