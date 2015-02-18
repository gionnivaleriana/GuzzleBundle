<?php

namespace Kopjra\GuzzleBundle\EventListener;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\EmitterInterface;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\SubscriberInterface;
use Psr\Log\LoggerInterface;

/**
 * Utility used to log requests and responses.
 */
class LogSubscriber implements SubscriberInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
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
     * Log before the request
     *
     * Example: [before] GET http://www.example.com/path
     *
     * @param BeforeEvent      $event
     * @param string           $name
     * @param EmitterInterface $emitter
     */
    public function onBefore(BeforeEvent $event, $name, EmitterInterface $emitter = null)
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
     * Log after a request is completed
     *
     * Example: [complete] 200 http://www.example.com/path
     *
     * @param CompleteEvent    $event
     * @param string           $name
     * @param EmitterInterface $emitter
     */
    public function onComplete(CompleteEvent $event, $name, EmitterInterface $emitter = null)
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
     * Log after an error is thrown or an error was received, 5XX are considered error
     * Example: [warning] 404 (Page Not Found) http://www.example.com/path
     * Example: [error] 500 (Server Error) http://www.example.com/path
     *
     * @param ErrorEvent       $event
     * @param string           $method
     * @param EmitterInterface $emitter
     */
    public function onError(ErrorEvent $event, $method = 'error', EmitterInterface $emitter = null)
    {
        $logger = $this->logger;
        $exception = $event->getException();
        $response = $event->getResponse();
        $message = sprintf(
            '[%s] %s (%s) %s',
            $method,
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $response->getEffectiveUrl()
        );

        $logger->$method($message);
        $logger->debug((string) $exception);
    }
}
