<?php

namespace Kopjra\GuzzleBundle\Subscribers;

use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\HasEmitterInterface;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Subscriber\Cache\CacheSubscriber as BaseSubscriber;

/**
 * Server and client caching utility.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 */
class CacheSubscriber implements SubscriberInterface
{
    /**
     * Set cache type to server or client.
     *
     * @var string
     */
    private $type;

    /**
     * Cache provider (both for client and server).
     *
     * @var Cache
     */
    private $cache;

    /**
     * [__construct description]
     */
    public function __construct(Cache $cache, $type)
    {
        $this->type = $type;
        $this->cache = $cache;
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        if ('server' === $this->type) {
            return [
                'before' => [
                    'onServerBefore',
                ],
                'complete' => [
                    'onServerComplete',
                ],
            ];
        }
    }

    /**
     * Intercepts a request if it's already cached and method is safe.
     *
     * @param BeforeEvent $event Guzzle 5 event.
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    public function onServerBefore(BeforeEvent $event)
    {
        $request = $event->getRequest();
        $cache = $this->cache;

        if (!in_array($request->getMethod(), ['GET', 'HEAD'])) {
            return;
        }

        $hash = sprintf(
            '%s %s',
            $request->getMethod(),
            $request->getUrl()
        );

        if ($cache->contains($hash)) {
            $response = $cache->fetch($hash);
            $event->intercept($response);
        }
    }

    /**
     * After a successful request, cache the response (if the method is safe).
     *
     * @param CompleteEvent $event
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    public function onServerComplete(CompleteEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $cache = $this->cache;

        if (!in_array($request->getMethod(), ['GET', 'HEAD'])) {
            return;
        }

        $hash = sprintf(
            '%s %s',
            $request->getMethod(),
            $request->getUrl()
        );

        $cache->save($hash, $response);
    }

    /**
     * @param HasEmitterInterface $client
     * @param array|null          $options
     */
    public function attach(HasEmitterInterface $client, array $options = null)
    {
        if ('client' === $this->type) {
            BaseSubscriber::attach($client, $options);
        }
    }
}
