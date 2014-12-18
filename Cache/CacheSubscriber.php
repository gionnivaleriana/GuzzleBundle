<?php

namespace Kopjra\GuzzleBundle\Cache;
use Doctrine\Common\Cache\ArrayCache;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\HasEmitterInterface;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Subscriber\Cache\CacheStorage;
use GuzzleHttp\Subscriber\Cache\PurgeSubscriber;
use GuzzleHttp\Subscriber\Cache\ValidationSubscriber;
use GuzzleHttp\Subscriber\Cache\Utils;


/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 */
class CacheSubscriber implements SubscriberInterface {

    /**
     * @var callable
     */
    protected $canCache;
    /**
     * @var CacheStorage
     */
    protected $storage;

    /**
     * Set cache type to server or client
     *
     * @var string
     */
    private $cacheType;

    /**
     *
     */
    public function __construct() {
        $this->storage = new CacheStorage(new ArrayCache());
        $this->canCache = ['self', 'canCacheRequest'];
    }

    /**
     * @return array
     */
    public function getEvents() {
        return [
            'before' => [
                'onBefore',
            ],
            'complete' => [
                'onComplete',
            ],
            'error' => [
                'onError',
            ],
        ];
    }

    /**
     * @param HasEmitterInterface $client
     * @param null $options
     */
    public function attach(HasEmitterInterface $client, $options = null) {
        if (!isset($options['storage'])) {
            $options['storage'] = new CacheStorage(new ArrayCache());
        }

        if (!isset($options['can_cache'])) {
            $options['can_cache'] = ['self', 'canCacheRequest'];
        }

        $emitter = $client->getEmitter();
        $emitter->attach(new self($options['storage'],$options['can_cache']));

        if (!isset($options['validate']) || $options['validate'] === true)
            $emitter->attach(
                new ValidationSubscriber($options['storage'],
                    $options['can_cache'])
            );

        if (!isset($options['purge']) || $options['purge'] === true)
            $emitter->attach(
                new PurgeSubscriber($options['storage'])
            );
    }

    /**
     * @param RequestInterface $request
     * @return bool
     */
    private function canCacheRequest(RequestInterface $request) {
        return !$request->getConfig()->get('cache.disable')
        && Utils::canCacheRequest($request);
    }

    /**
     * @param BeforeEvent $event
     */
    public function onBefore(BeforeEvent $event) {
        $request = $event->getRequest();

        if (!$this->canCacheRequest($request)) {
            $this->cacheMiss($request);
            return;
        }

        if (!$response = $this->storage->fetch($request)) {
            $this->cacheMiss($request);
            return;
        }
        // FIXME: Check if it really prevents the http call
        // If server type cache is setted intercepts the http request,
        // sets the response as valid
        // and outputs the cache copy
        if ($this->cacheType == "server") {
            $event->intercept($response);
            $valid = true;
        }
        // Otherwise (client cache) validates the request based on
        // received response after a http call
        else {
            $response->setHeader('Age', Utils::getResponseAge($response));
            $valid = $this->validate($request, $response);
        }


        // Validate that the response satisfies the request
        if ($valid) {
            $request->getConfig()->set('cache_lookup', 'HIT');
            $request->getConfig()->set('cache_hit', true);
            $event->intercept($response);
        } else {
            $this->cacheMiss($request);
        }
    }

    /**
     * @param CompleteEvent $event
     */
    public function onComplete(CompleteEvent $event) {
        $request = $event->getRequest();
        $response = $event->getResponse();

        // Cache the response if it can be cached and isn't already
        if ($request->getConfig()->get('cache_lookup') === 'MISS'
            && Utils::canCacheResponse($response)
        ) {
            $this->storage->cache($request, $response);
            var_dump($this->storage);
        }

        $this->addResponseHeaders($request, $response);
    }

    /**
     * @param ErrorEvent $event
     */
    public function onError(ErrorEvent $event) {
        $request = $event->getRequest();

        if (!self::canCacheRequest($request)) {
            return;
        }

        $response = $this->storage->fetch($request);

        // Intercept the failed response if possible
        if ($response && $this->validateFailed($request, $response)) {
            $request->getConfig()->set('cache_hit', 'error');
            $response->setHeader('Age', Utils::getResponseAge($response));
            $this->addResponseHeaders($request, $response);
            $event->intercept($response);
        }
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    private function validateFailed(RequestInterface $request, ResponseInterface $response) {
        $req = Utils::getDirective($request, 'stale-if-error');
        $res = Utils::getDirective($response, 'stale-if-error');

        if (!$req && !$res) {
            return false;
        }

        $responseAge = Utils::getResponseAge($response);
        $maxAge = Utils::getMaxAge($response);

        if (($req && $responseAge - $maxAge > $req) ||
            ($responseAge - $maxAge > $res)
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param RequestInterface $request
     */
    private function cacheMiss(RequestInterface $request) {
        $request->getConfig()->set('cache_lookup', 'MISS');
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    private function validate(RequestInterface $request, ResponseInterface $response) {
        if (Utils::getDirective($response, 'must-revalidate')) {
            return true;
        }
        return Utils::isResponseValid($request, $response);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    private function addResponseHeaders(RequestInterface $request, ResponseInterface $response) {
        $params = $request->getConfig();
        $lookup = $params['cache_lookup'] . ' from GuzzleCache';
        $response->addHeader('X-Cache-Lookup', $lookup);

        switch($params['cache_hit']){
            case true:
                $response->addHeader('X-Cache', 'HIT from GuzzleCache');
                break;
            case 'error':
                $response->addHeader('X-Cache', 'HIT_ERROR from GuzzleCache');
                break;
            default: $response->addHeader('X-Cache', 'MISS from GuzzleCache');
        }

        $freshness = Utils::getFreshness($response);

        if ($freshness !== null && $freshness <= 0) {
            $response->addHeader(
                'Warning',
                sprintf(
                    '%d GuzzleCache/' . ClientInterface::VERSION . ' "%s"',
                    110,
                    'Response is stale'
                )
            );
        }
    }

    /**
     * @return mixed
     */
    public function getCacheType() {
        return $this->cacheType;
    }

    /**
     * @param mixed $cacheType
     */
    public function setCacheType($cacheType) {
        $this->cacheType = $cacheType == "server" ? "server" : "client";
    }
}