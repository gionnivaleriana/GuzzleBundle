<?php

namespace Kopjra\GuzzleBundle\Tests\Subscribers;

use Kopjra\GuzzleBundle\Tests\AppKernel;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Kopjra\GuzzleBundle\Subscribers\CacheSubscriber
 */
class CacheSubscriberTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::attach
     */
    public function testDefaultClientCache()
    {
        $kernel = new AppKernel('CacheSubscriberTest_testDefaultClientCache', true);
        $kernel->boot();

        $this->assertTrue(false);
    }

    /**
     * @covers ::attach
     * @covers ::getEvents
     * @covers ::onBefore
     * @covers ::onComplete
     */
    public function testDefaultServerCache()
    {
        $kernel = new AppKernel('CacheSubscriberTest_testDefaultServerCache', true);
        $kernel->boot();

        $this->assertTrue(false);
    }

    /**
     * @covers ::attach
     */
    public function testConfiguredClientCache()
    {
        $kernel = new AppKernel('CacheSubscriberTest_testConfiguredClientCache', true);
        $kernel->boot();

        $this->assertTrue(false);
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::getEvents
     * @covers ::onBefore
     * @covers ::onComplete
     */
    public function testConfiguredServerCache()
    {
        $kernel = new AppKernel('CacheSubscriberTest_testConfiguredServerCache', true);
        $kernel->boot();

        $this->assertTrue(false);
    }
}
