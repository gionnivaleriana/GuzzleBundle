<?php

namespace Kopjra\GuzzleBundle\Tests;

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
        //$this->Services = new Services();
        $this->markTestSkipped( 'Kopjra\GuzzleBundle\Services\Services must be fixed first' );
    }

    public function testServices() {
        $this->assertInstanceOf( '\GuzzleHttp\Event\SubscriberInterface', $this->Services );
    }

}
