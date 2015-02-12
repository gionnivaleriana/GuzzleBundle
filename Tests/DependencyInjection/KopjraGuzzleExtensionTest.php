<?php

namespace Kopjra\GuzzleBundle\Tests;

use Kopjra\GuzzleBundle\DependencyInjection\KopjraGuzzleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class KopjraGuzzleExtensionTest
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 11/02/15
 * @package Kopjra\GuzzleBundle\Tests
 */
class KopjraGuzzleExtensionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Kopjra\GuzzleBundle\DependencyInjection\KopjraGuzzleExtension
	 */
	protected $KopjraGuzzleExtension;

	public function testKopjraGuzzleExtension() {
		$this->KopjraGuzzleExtension = new KopjraGuzzleExtension();
		$this->KopjraGuzzleExtension->load( [ ], new ContainerBuilder() );
	}

}
