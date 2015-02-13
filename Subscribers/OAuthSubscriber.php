<?php

namespace Kopjra\GuzzleBundle\Subscribers;

use GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
 * Class OAuthSubscriber.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\OAuth
 */
class OAuthSubscriber extends Oauth1
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
    }

    /**
     * @param array $config
     *
     * @return \GuzzleHttp\Subscriber\Oauth\Oauth1
     */
    public function config(array $config)
    {
        return new parent($config);
    }
}