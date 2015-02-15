<?php
/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 15/02/15
 * @pakage GuzzleBundle
 */

namespace Kopjra\GuzzleBundle\Tests\DependencyInjection\Configuration;


use Kopjra\GuzzleBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Configuration
     */
    protected $Configuration;

    public function setUp(){
        $this->Configuration = new Configuration();
    }

    public function testGetConfigTreeBuilder(){
        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\Builder\TreeBuilder',
            $this->Configuration->getConfigTreeBuilder()
        );
    }

}
