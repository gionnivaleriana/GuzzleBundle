<?php

namespace Kopjra\GuzzleBundle\Tests;

use Kopjra\GuzzleBundle\DependencyInjection\KopjraGuzzleExtension;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class KopjraGuzzleExtensionTest.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\Tests
 */
class KopjraGuzzleExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * [testKopjraGuzzleExtension description]
     *
     * @return [type] [description]
     */
    public function testKopjraGuzzleExtension()
    {
        $extension = new KopjraGuzzleExtension();
        $extension->load([ ], new ContainerBuilder());
    }
}
