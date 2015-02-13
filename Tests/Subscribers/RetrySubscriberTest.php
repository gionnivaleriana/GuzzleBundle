<?php

namespace Kopjra\GuzzleBundle\Tests\Subscribers;

use Exception;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\ResponseInterface;
use Kopjra\GuzzleBundle\Subscribers\RetrySubscriber;
use PHPUnit_Framework_TestCase;

/**
 * Class RetrySubscriberTest.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\Tests
 */
class RetrySubscriberTest extends PHPUnit_Framework_TestCase
{
    /**
     * [testCreatesDefaultStatusFilter description]
     *
     * @return [type] [description]
     */
    public function testCreatesDefaultStatusFilter()
    {
        $filter = RetrySubscriber::createStatusFilter();

        $event = $this->createEvent(new Response(500));
        $this->assertTrue($filter(1, $event));

        $event = $this->createEvent(new Response(503));
        $this->assertTrue($filter(0, $event));

        $event = $this->createEvent(new Response(200));
        $this->assertFalse($filter(1, $event));
    }

    /**
     * [createEvent description]
     *
     * @param ResponseInterface|null $response     [description]
     * @param RequestInterface|null  $request      [description]
     * @param Exception|null         $exception    [description]
     * @param array                  $transferInfo [description]
     * @param string                 $type         [description]
     *
     * @return [type] [description]
     */
    private function createEvent(
        ResponseInterface $response = null,
        RequestInterface $request = null,
        Exception $exception = null,
        array $transferInfo = array(),
        $type = 'GuzzleHttp\\Event\\AbstractTransferEvent'
    ) {
        if (!$request) {
            $request = new Request('GET', 'http://www.foo.com');
        }

        $event = $this->getMockBuilder($type)
            ->setMethods(['getResponse', 'getTransferInfo', 'getRequest', 'getException'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $event->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $event->expects($this->any())
            ->method('getException')
            ->will($this->returnValue($exception));

        $event->expects($this->any())
            ->method('getTransferInfo')
            ->will($this->returnCallback(function ($arg) use ($transferInfo) {
                return $arg ? (isset($transferInfo[$arg]) ? $transferInfo[$arg] : null) : $transferInfo;
            }));

        return $event;
    }

    /**
     * [testCreatesCustomStatusFilter description]
     *
     * @return [type] [description]
     */
    public function testCreatesCustomStatusFilter()
    {
        $filter = RetrySubscriber::createStatusFilter([202, 304]);

        $event = $this->createEvent(new Response(500));
        $this->assertFalse($filter(1, $event));

        $event = $this->createEvent(new Response(503));
        $this->assertFalse($filter(0, $event));

        $event = $this->createEvent(new Response(202));
        $this->assertTrue($filter(1, $event));

        $event = $this->createEvent();
        $this->assertFalse($filter(1, $event));
    }
}
