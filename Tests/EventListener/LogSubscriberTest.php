<?php

namespace Kopjra\GuzzleBundle\Tests\EventListener;

use Kopjra\GuzzleBundle\EventListener\LogSubscriber;
use Kopjra\GuzzleBundle\Tests\Resources\app\AppKernel;
use Symfony\Component\HttpKernel\Tests\Logger;

/**
 * Class LogSubscriberTest.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\Tests\EventListener
 */
class LogSubscriberTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var LogSubscriber
     */
    protected $subscriber;

    /**
     * [setUp description]
     */
    public function setUp()
    {
        $this->subscriber = new LogSubscriber(new Logger());
        $kernel = new AppKernel('dev', true);
        $kernel->boot();
    }

    /**
     * [testGetEvents description]
     *
     * @return [type] [description]
     */
    public function testGetEvents()
    {
        $eventsArray = $this->subscriber->getEvents();

        $this->assertArrayHasKey('before', $eventsArray);
        $this->assertArrayHasKey('complete', $eventsArray);
        $this->assertArrayHasKey('error', $eventsArray);
    }
}
