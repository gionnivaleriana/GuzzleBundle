<?php

namespace Kopjra\GuzzleBundle\Subscribers;

use GuzzleHttp\Event\AbstractRetryableEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Subscriber\Log\Formatter;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 */
class RetrySubscriber implements SubscriberInterface
{

    const RETRY = true;
    const DEFER = false;
    const BREAK_CHAIN = -1;
    const MSG_FORMAT = '[{ts}] {method} {url} - {code} {phrase} - Retries: {retries}, Delay: {delay}, Time: {connect_time}, {total_time}, Error: {error}';

    /**
     * [$filter description]
     *
     * @var callable
     */
    private $filter;

    /**
     * [$delayFn description]
     *
     * @var callable
     */
    private $delayFn;

    /**
     * [$maxRetries description]
     *
     * @var int
     */
    private $maxRetries;

    /**
     * @param array $config
     */
    public function config(array $config)
    {
        if (!isset($config['filter'])) {
            throw new InvalidArgumentException('A "filter" is required');
        }

        $this->filter = $config['filter'];
        $this->delayFn = isset($config['delay'])
            ? $config['delay']
            : [__CLASS__, 'exponentialDelay'];
        $this->maxRetries = isset($config['max'])
            ? $config['max']
            : 5;
    }

    /**
     * @param $retries
     *
     * @return int
     */
    public static function exponentialDelay($retries)
    {
        return (int) pow(2, $retries - 1);
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return [
            'complete' => ['onComplete', RequestEvents::VERIFY_RESPONSE + 100],
            'error'    => ['onComplete', RequestEvents::LATE],
        ];
    }

    /**
     * @param AbstractRetryableEvent $event
     */
    public function onComplete(AbstractRetryableEvent $event)
    {
        $request = $event->getRequest();
        $config = $request->getConfig();
        $retries = (int) $config['retries'];
        if ($retries >= $this->maxRetries) {
            return;
        }
        $filterFn = $this->filter;
        if ($filterFn($retries, $event)) {
            $delayFn = $this->delayFn;
            $config['retries'] = ++$retries;
            $event->retry($delayFn($retries, $event));
        }
    }

    /**
     * @param  array    $filters
     * @return callable
     */
    public static function createChainFilter(array $filters)
    {
        return function ($retries, AbstractRetryableEvent $event) use ($filters) {
            foreach ($filters as $filter) {
                $result = $filter($retries, $event);
                if ($result === self::RETRY) {
                    return true;
                }
                if ($result === self::BREAK_CHAIN) {
                    return false;
                }
            }

            return false;
        };
    }

    /**
     * @return callable
     */
    public static function createIdempotentFilter()
    {
        static $retry = ['GET' => true, 'HEAD' => true, 'PUT' => true,
            'DELETE' => true, 'OPTIONS' => true, 'TRACE' => true, ];

        return function ($retries, AbstractRetryableEvent $e) use ($retry) {
            return isset($retry[$e->getRequest()->getMethod()])
                ? self::DEFER
                : self::BREAK_CHAIN;
        };
    }

    /**
     * @param  array    $failureStatuses
     * @return callable
     */
    public static function createStatusFilter(array $failureStatuses = [500, 503])
    {
        $failureStatuses = array_fill_keys($failureStatuses, true);

        return function ($retries, $event) use ($failureStatuses) {
            if (!($response = $event->getResponse())) {
                return false;
            }

            return isset($failureStatuses[$response->getStatusCode()]);
        };
    }

    /**
     * @param  callable        $delayFn
     * @param  LoggerInterface $logger
     * @param  null            $formatter
     * @return callable
     */
    public static function createLoggingDelay(callable $delayFn, LoggerInterface $logger, $formatter = null)
    {
        if (!$formatter) {
            $formatter = new Formatter(self::MSG_FORMAT);
        }
        if (!($formatter instanceof Formatter)) {
            $formatter = new Formatter($formatter);
        }

        return function ($retries, AbstractRetryableEvent $event) use ($delayFn, $logger, $formatter) {
            $delay = $delayFn($retries, $event);

            $logger->log(LogLevel::NOTICE, $formatter->format(
                $event->getRequest(),
                $event->getResponse(),
                $event instanceof ErrorEvent
                    ? $event->getException()
                    : null, ['retries' => $retries + 1, 'delay'   => $delay] + $event->getTransferInfo()
            ));

            return $delay;
        };
    }

    /**
     * @param  null     $errorCodes
     * @return callable
     */
    public static function createCurlFilter($errorCodes = null)
    {
        $errorCodes = $errorCodes ?: [
            CURLE_OPERATION_TIMEOUTED,
            CURLE_COULDNT_RESOLVE_HOST,
            CURLE_COULDNT_CONNECT,
            CURLE_SSL_CONNECT_ERROR,
            CURLE_GOT_NOTHING,
        ];

        $errorCodes = array_fill_keys($errorCodes, 1);

        return function ($retries, AbstractRetryableEvent $event) use ($errorCodes) {
            return isset($errorCodes[(int) $event->getTransferInfo('curl_result')]);
        };
    }

    /**
     * @return callable
     */
    public static function createConnectFilter()
    {
        return function ($retries, AbstractRetryableEvent $event) {
            return $event instanceof ErrorEvent && $event->getException() instanceof ConnectException;
        };
    }
}
