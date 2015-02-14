<?php

namespace Kopjra\GuzzleBundle\Subscribers;

use GuzzleHttp\Subscriber\Oauth\Oauth1 as BaseSubscriber;

/**
 * Class OAuthSubscriber.
 *
 * @author Joy Lazari <joy.lazari@gmail.com>
 *
 * @package Kopjra\GuzzleBundle\OAuth
 */
class OAuthSubscriber extends BaseSubscriber
{

    /**
     * @inheritdoc
     * @param array $config
     */
    public function __construct(array $config)
    {
        $keys = [
            'request_method',
            'callback',
            'consumer_key',
            'consumer_secret',
            'token',
            'token_secret',
            'verifier',
            'version',
            'realm',
            'signature_method'
        ];
        parent::__construct(array_combine($keys, $config));
    }
}
