<?php

namespace Kopjra\GuzzleBundle\Subscribers;

use GuzzleHttp\Subscriber\Retry\RetrySubscriber as BaseSubscriber;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 */
class RetrySubscriber extends BaseSubscriber
{
    public function __construct(array $config){
        define("RETRY_SUBSCRIBER_DEFAULT_DELAY", $config[1]);
        $config = [
            'filter' => parent::createStatusFilter($config[0]),
            'delay'  => function () { return RETRY_SUBSCRIBER_DEFAULT_DELAY; },
            'max' => $config[2]
        ];
        parent::__construct($config);
    }

    public function config(array $config){
        return new BaseSubscriber($config);
    }
}
