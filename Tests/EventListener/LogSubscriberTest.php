<?php

namespace Kopjra\GuzzleBundle\Tests\EventListener;

use GuzzleHttp\Client;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Transaction;
use Kopjra\GuzzleBundle\EventListener\Emitter;
use Kopjra\GuzzleBundle\EventListener\LogSubscriber;
use Kopjra\GuzzleBundle\Tests\Resources\app\AppKernel;
use Symfony\Component\HttpKernel\Tests\Logger;

/**
 * Class LogSubscriberTest
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 12/02/15
 * @package Kopjra\GuzzleBundle\Tests\EventListener
 */
class LogSubscriberTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var LogSubscriber
	 */
	protected $LogSubscriber;

	public function setUp() {
		$this->LogSubscriber = new LogSubscriber( new Logger() );
	}

	public function testGetEvents() {
		$eventsArray = $this->LogSubscriber->getEvents();
		$this->assertArrayHasKey( 'before', $eventsArray );
		$this->assertArrayHasKey( 'complete', $eventsArray );
		$this->assertArrayHasKey( 'error', $eventsArray );
	}

}
