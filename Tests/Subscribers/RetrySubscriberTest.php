<?php
namespace Kopjra\GuzzleBundle\Tests\Subscribers;

use Kopjra\GuzzleBundle\Subscribers\RetrySubscriber;
use Kopjra\GuzzleBundle\Tests\AppKernel;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-02-13 at 15:55:19.
 */
class RetrySubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RetrySubscriber
     */
    protected $RetrySubscriber;

    /**
     * @var \Symfony\Component\HttpKernel\Kernel
     */
    private $kernel;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        $this->kernel = new AppKernel('KopjraGuzzleExtensionTest', true);
        $this->kernel->boot();
        $this->RetrySubscriber = $this->kernel->getContainer()->get('kopjra.guzzle.subscribers.retry');
    }

    public function testService() {
        $this->assertTrue($this->kernel->getContainer()->has('kopjra.guzzle.subscribers.retry'));
        $this->assertInstanceOf('GuzzleHttp\\Event\\SubscriberInterface', $this->RetrySubscriber);
    }

    public function testConfig(){
        $fromControllerConfig = [
            'filter' => RetrySubscriber::createStatusFilter(),
            'delay'  => function () { return 1000; },
            'max' => 5
        ];
        $this->assertInstanceOf(
            'GuzzleHttp\Event\SubscriberInterface',
            $this->RetrySubscriber->config($fromControllerConfig)
        );
    }
}
