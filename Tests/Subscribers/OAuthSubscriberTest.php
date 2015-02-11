<?php

namespace Kopjra\GuzzleBundle\Tests\Subscribers;

use Kopjra\GuzzleBundle\Subscribers\OAuthSubscriber;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 03/01/15
 *
 * Class OAuthSubscriberTest
 * @package Kopjra\GuzzleBundle\Tests
 */
class OAuthSubscriberTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var OAuthSubscriber
	 */
	protected $OAuthSubscriber;

	public function setUp() {
		$this->OAuthSubscriber = new OAuthSubscriber();
	}

	public function testOAuthSubscriber() {
		$this->assertInstanceOf( '\GuzzleHttp\Event\SubscriberInterface', $this->OAuthSubscriber );
	}

}
