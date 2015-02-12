<?php

namespace Kopjra\GuzzleBundle\Tests;

use Kopjra\GuzzleBundle\DependencyInjection\Configuration;

/**
 * Class ConfigurationTest
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 11/02/15
 * @package Kopjra\GuzzleBundle\Tests
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase {

	protected $Configuration;

	public function testConfiguration() {
		$this->Configuration = new Configuration();
		$this->assertInstanceOf(
			'Symfony\Component\Config\Definition\Builder\TreeBuilder',
			$this->Configuration->getConfigTreeBuilder()
		);
	}

}
