<?php

namespace Kopjra\GuzzleBundle\Tests\Subscribers;

use GuzzleHttp\Client;
use Kopjra\GuzzleBundle\Subscribers\CacheSubscriber;
use PHPUnit_Framework_TestCase;

/**
 * Class CacheSubscriberTest.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\Tests
 */
class CacheSubscriberTest extends PHPUnit_Framework_TestCase
{
    /**
     * [testAttachCacheSubscriberToTheGuzzleClient description]
     *
     * @return [type] [description]
     */
    public function testAttachCacheSubscriberToTheGuzzleClient()
    {
        $client = new Client();
        $cache = new CacheSubscriber();

        $client->getEmitter()->attach($cache);

        $this->assertObjectHasAttribute('storage', $cache);
        $this->assertAttributeInstanceOf(
            'GuzzleHttp\\Subscriber\\Cache\\CacheStorage',
            $cache
        );

        $this->assertTrue($client->getEmitter()->hasListeners('error'));
    }
}
