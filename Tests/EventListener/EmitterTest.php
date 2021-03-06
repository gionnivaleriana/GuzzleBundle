<?php

namespace Kopjra\GuzzleBundle\Tests\EventListener;

use Kopjra\GuzzleBundle\EventListener\Emitter;

/**
 * Class EmitterTest.
 * @author  Joy Lazari <joy.lazari@gmail.com>
 * @package Kopjra\GuzzleBundle\Tests\EventListener
 */
class EmitterTest extends \PHPUnit_Framework_TestCase {
    public function testEmitter() {
        $this->assertInstanceOf('GuzzleHttp\\Event\\EmitterInterface', new Emitter());
    }
}
