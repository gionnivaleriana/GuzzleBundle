<?php

namespace Kopjra\GuzzleBundle\EventListener;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\EmitterInterface;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\SubscriberInterface;
use Psr\Log\LoggerInterface;

/**
 * ...
 */
class LogSubscriber implements SubscriberInterface
{
    /**
     * ...
     */
    private $logger;

    /**
     * ...
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * ...
     */
    public function getEvents()
    {
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
     * ...
     */
    public function onBefore(BeforeEvent $event, $name, EmitterInterface $emitter)
    {
        $logger = $this->logger;
        $request = $event->getRequest();
        $message = sprintf(
            '[%s] %s %s',
            $name,
            $request->getMethod(),
            $request->getUrl()
        );

        $logger->info($message);
        $logger->debug((string) $request);
    }

    /**
     * ...
     */
    public function onComplete(CompleteEvent $event, $name, EmitterInterface $emitter)
    {
        $logger = $this->logger;
        $response = $event->getResponse();
        $message = sprintf(
            '[%s] %s %s',
            $name,
            $response->getStatusCode(),
            $response->getEffectiveUrl()
        );

        $logger->info($message);
        $logger->debug((string) $response);
    }

    /**
     * ...
     */
    public function onError(ErrorEvent $event, $name, EmitterInterface $emitter)
    {
        $logger = $this->logger;
        $exception = $event->getException();
        $response = $event->getResponse();

        $method = $exception->getThrowImmediately() ? 'error' : 'warning';
        $message = sprintf(
            '[%s] %s (%s) %s',
            $name,
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $response->getEffectiveUrl()
        );

        $logger->$method($message);
        $logger->debug((string) $exception);
    }
}
