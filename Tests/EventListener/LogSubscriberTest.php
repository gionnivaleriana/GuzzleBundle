<?php
namespace Kopjra\GuzzleBundle\Tests\EventListener;

use Kopjra\GuzzleBundle\EventListener\LogSubscriber;

/**
 * Class LogSubscriberTest
 * @author  Joy Lazari <joy.lazari@gmail.com>
 * @date    15/02/15
 * @package Kopjra\GuzzleBundle\Tests\EventListener
 */
class LogSubscriberTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var LogSubscriber
     */
    protected $LogSubscriber;
    /**
     * @var \GuzzleHttp\Message\Request|\PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    protected $MockedRequest;
    /**
     * @var \GuzzleHttp\Message\Response|\PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    protected $MockedResponse;
    /**
     * @var \GuzzleHttp\Event\BeforeEvent|\PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    protected $BeforeEvent;
    /**
     * @var \GuzzleHttp\Event\CompleteEvent|\PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    protected $CompleteEvent;
    /**
     * @var \GuzzleHttp\Event\ErrorEvent|\PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    protected $ErrorEvent;

    protected function setUp() {
        $this->LogSubscriber = new LogSubscriber(new \Symfony\Component\HttpKernel\Tests\Logger());

        $this->MockedRequest = $this->getMockBuilder('GuzzleHttp\Message\Request')->disableOriginalConstructor()->getMock();
        $this->MockedRequest->method('getMethod')->willReturn('dummy string');
        $this->MockedRequest->method('getUrl')->willReturn('dummy string');

        $this->MockedResponse = $this->getMockBuilder('GuzzleHttp\Message\Response')->disableOriginalConstructor()->getMock();
        $this->MockedResponse->method('getStatusCode')->willReturn('dummy string');
        $this->MockedResponse->method('getEffectiveUrl')->willReturn('dummy string');
        $this->MockedResponse->method('getReasonPhrase')->willReturn('dummy string');

        $this->BeforeEvent = $this->getMockBuilder('GuzzleHttp\Event\BeforeEvent')->disableOriginalConstructor()->getMock();
        $this->BeforeEvent->method('getRequest')->willReturn($this->MockedRequest);

        $this->CompleteEvent = $this->getMockBuilder('GuzzleHttp\Event\CompleteEvent')->disableOriginalConstructor()->getMock();
        $this->CompleteEvent->method('getResponse')->willReturn($this->MockedResponse);

        $this->ErrorEvent = $this->getMockBuilder('GuzzleHttp\Event\ErrorEvent')->disableOriginalConstructor()->getMock();
        $this->ErrorEvent->method('getResponse')->willReturn($this->MockedResponse);
        $this->ErrorEvent->method('getException')->willReturn(new \Exception());
    }

    /**
     * @covers Kopjra\GuzzleBundle\EventListener\LogSubscriber::__construct
     */
    public function testLogSubscriber(){
        $this->assertInstanceOf(
            'GuzzleHttp\Event\SubscriberInterface',
            new LogSubscriber(new \Symfony\Component\HttpKernel\Tests\Logger())
        );
    }

    /**
     * @covers Kopjra\GuzzleBundle\EventListener\LogSubscriber::getEvents
     */
    public function testGetEvents() {
        $events = $this->LogSubscriber->getEvents();
        $this->assertArrayHasKey('before', $events);
        $this->assertArrayHasKey('complete', $events);
        $this->assertArrayHasKey('error', $events);
    }

    /**
     * @covers Kopjra\GuzzleBundle\EventListener\LogSubscriber::onBefore
     */
    public function testOnBefore() {
        $this->LogSubscriber->onBefore($this->BeforeEvent, 'before');
    }

    /**
     * @covers Kopjra\GuzzleBundle\EventListener\LogSubscriber::onComplete
     */
    public function testOnComplete() {
        $this->LogSubscriber->onComplete($this->CompleteEvent, 'complete');
    }

    /**
     * @covers Kopjra\GuzzleBundle\EventListener\LogSubscriber::onError
     */
    public function testOnError() {
        $this->LogSubscriber->onError($this->ErrorEvent, 'error');
    }
}
