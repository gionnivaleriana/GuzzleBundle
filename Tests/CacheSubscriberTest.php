<?php

namespace Kopjra\GuzzleBundle\Tests;
use GuzzleHttp\Client;
use Kopjra\GuzzleBundle\Cache\CacheSubscriber;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 03/01/15
 *
 * Class CacheSubscriberTest
 * @package Kopjra\GuzzleBundle\Tests
 */
class CacheSubscriberTest extends \PHPUnit_Framework_TestCase {

    function testAttachCacheSubscriberToTheGuzzleClient() {
        $client = new Client();
        $cache = new CacheSubscriber();
        $client->getEmitter()->attach($cache);
        //$this->assertObjectHasAttribute('subscriber', $cache);
        $this->assertObjectHasAttribute('storage', $cache);
        $this->assertAttributeInstanceOf(
            'GuzzleHttp\Subscriber\Cache\CacheStorage',
            "storage", $cache
        );
        /*
        $this->assertAttributeInstanceOf(
            'GuzzleHttp\Subscriber\Cache\CacheSubscriber',
            "subscriber", $cache
        );
        */
        $this->assertTrue($client->getEmitter()->hasListeners('error'));
    }


}