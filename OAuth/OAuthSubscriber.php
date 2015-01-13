<?php

namespace Kopjra\GuzzleBundle\OAuth;

use GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
 * Class OAuthSubscriber
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @package Kopjra\GuzzleBundle\OAuth
 */
class OAuthSubscriber extends Oauth1 {

    function __construct() {}

    /**
     * @param array $config
     * @return \GuzzleHttp\Subscriber\Oauth\Oauth1
     */
    public function config(Array $config) {
        return new parent($config);
    }
}