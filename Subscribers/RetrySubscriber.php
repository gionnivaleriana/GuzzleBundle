<?php

namespace Kopjra\GuzzleBundle\Subscribers;

use GuzzleHttp\Subscriber\Retry\RetrySubscriber as BaseSubscriber;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 */
class RetrySubscriber extends BaseSubscriber
{
    public function __construct(array $config){
        $config = [
            'filter' => parent::createStatusFilter($config[0]),
            'delay'  => function () { global $config; return $config[1]; },
            'max' => $config[2]
        ];
        parent::__construct($config);
    }

    public function config(array $config){
        return new BaseSubscriber($config);
    }
}
