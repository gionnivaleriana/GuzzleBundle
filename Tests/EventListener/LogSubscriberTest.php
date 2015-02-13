<?php

namespace Kopjra\GuzzleBundle\Tests\EventListener;

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
		$kernel = new AppKernel( 'dev', true );
		$kernel->boot();
		$container = $kernel->getContainer();
		$container->get( 'guzzle' );
	}

	public function testGetEvents() {
		$eventsArray = $this->LogSubscriber->getEvents();
		$this->assertArrayHasKey( 'before', $eventsArray );
		$this->assertArrayHasKey( 'complete', $eventsArray );
		$this->assertArrayHasKey( 'error', $eventsArray );
	}

}
