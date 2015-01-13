<?php

namespace Kopjra\GuzzleBundle\Tests;

use GuzzleHttp\Client;
use Kopjra\GuzzleBundle\Services\Services;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 31/12/14
 *
 * Class ServicesTest
 * @package Kopjra\GuzzleBundle\Tests
 */
class ServicesTest extends \PHPUnit_Framework_TestCase {

    function testAttachService() {
        $service = [
            'baseUrl' => 'http://httpbin.org/',
            'operations' => [
                'foo' => [
                    'httpMethod' => 'GET',
                    'uri' => '/get?{bar}',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'bar' => [
                            'type' => 'string',
                            'location' => 'uri'
                        ],
                        'baz' => [
                            'type' => 'string',
                            'location' => 'query'
                        ]
                    ]
                ]
            ],
            'models' => [
                'getResponse' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json'
                    ]
                ]
            ]
        ];
        $client = new Client();
        $services = new Services();
        $client = $services->attachWebService($client, $service);

        // $client should be an instance of GuzzleClient
        $this->assertInstanceOf("GuzzleHttp\\Command\\Guzzle\\GuzzleClient", $client);

        // If the service['operation']['foo'] has been correctly interpretated as method by
        // GuzzleHttp\Command than $cliens->foo() should not throw InvalidArgumentException
        try {
            $client->foo();
        }
        catch (\InvalidArgumentException $e) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

}
