<?php

namespace Kopjra\GuzzleBundle\Tests\Subscribers;

use Kopjra\GuzzleBundle\Subscribers\OAuthSubscriber;
use PHPUnit_Framework_TestCase;

/**
 * Class OAuthSubscriberTest.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\Tests
 */
class OAuthSubscriberTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var OAuthSubscriber
     */
    protected $subscriber;

    /**
     * [setUp description]
     */
    public function setUp()
    {
        $this->subscriber = new OAuthSubscriber();
    }

    /**
     * [testOAuthSubscriber description]
     *
     * @return [type] [description]
     */
    public function testOAuthSubscriber()
    {
        $this->assertInstanceOf(
            'GuzzleHttp\\Event\\SubscriberInterface',
            $this->subscriber
        );
    }

    /**
     * [testConfig description]
     *
     * @return [type] [description]
     */
    public function testConfig()
    {
        $oauth = $this->subscriber->config([
            'consumer_key'     => 'key',
            'consumer_secret'  => 'secret',
            'signature_method' => 'HMAC-SHA1',
        ]);

        $this->assertInstanceOf(
            'GuzzleHttp\\Event\\SubscriberInterface',
            $oauth
        );
    }
}
