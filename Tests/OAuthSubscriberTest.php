<?php

namespace Kopjra\GuzzleBundle\Tests;

use GuzzleHttp\Message\Request;
use GuzzleHttp\Post\PostBody;
use Kopjra\GuzzleBundle\OAuth\OAuthSubscriber;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 03/01/15
 *
 * Class OAuthSubscriberTest
 * @package Kopjra\GuzzleBundle\Tests
 */
class OAuthSubscriberTest extends \PHPUnit_Framework_TestCase {

    const TIMESTAMP = '1327274290';
    const NONCE = 'e7aa11195ca58349bec8b5ebe351d3497eb9e603';

    private $config = [
        'consumer_key'    => 'foo',
        'consumer_secret' => 'bar',
        'token'           => 'count',
        'token_secret'    => 'dracula'
    ];

    /**
     * @return Request
     */
    protected function getRequest() {
        $body = new PostBody();
        $body->setField('e', 'f');

        return new Request('POST', 'http://www.test.com/path?a=b&c=d', [], $body);
    }

    public function testSubscribesToEvents() {
        $events = (new OAuthSubscriber([]))->getEvents();
        $this->assertArrayHasKey('before', $events);
    }

    public function testAcceptsConfigurationData() {
        $p = new OAuthSubscriber($this->config);

        // Access the config object
        $class = new \ReflectionClass($p);
        $property = $class->getProperty('config');
        $property->setAccessible(true);
        $config = $property->getValue($p);

        $this->assertEquals('foo', $config['consumer_key']);
        $this->assertEquals('bar', $config['consumer_secret']);
        $this->assertEquals('count', $config['token']);
        $this->assertEquals('dracula', $config['token_secret']);
        $this->assertEquals('1.0', $config['version']);
        $this->assertEquals('HMAC-SHA1', $config['signature_method']);
        $this->assertEquals('header', $config['request_method']);
    }

}
