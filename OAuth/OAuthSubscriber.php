<?php

namespace Kopjra\GuzzleBundle\OAuth;

use GuzzleHttp\Collection;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Post\PostBodyInterface;
use GuzzleHttp\Query;
use GuzzleHttp\Url;


/**
 * Class OAuthSubscriber
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @package Kopjra\GuzzleBundle\OAuth
 */
class OAuthSubscriber implements SubscriberInterface {

    const REQUEST_METHOD_HEADER         = 'header';
    const REQUEST_METHOD_QUERY          = 'query';
    const SIGNATURE_METHOD_HMAC         = 'Hmac-Sha1';
    const SIGNATURE_METHOD_RSA          = 'Rsa-Sha1';
    const SIGNATURE_METHOD_PLAINTEXT    = 'Plain-Text';

    /**
     * @var Collection
     */
    public $config;

    /**
     * @param array $config
     */
    public function __construct($config = []) {
        if(!empty($config)) {
            $this->config = Collection::fromConfig($config, [
                'version' => '1.0',
                'request_method' => self::REQUEST_METHOD_HEADER,
                'consumer_key' => 'anonymous',
                'consumer_secret' => 'anonymous',
                'signature_method' => self::SIGNATURE_METHOD_HMAC,
            ], ['signature_method', 'version', 'consumer_key', 'consumer_secret']);
        }
    }

    /**
     * Set up the configuration for the OAuth
     *
     * @param array $config
     */
    public function config(Array $config) {
        $this->config = Collection::fromConfig($config, [
            'version' => '1.0',
            'request_method' => 'header',
            'consumer_key' => 'anonymous',
            'consumer_secret' => 'anonymous',
            'signature_method' => 'HMAC-SHA1',
        ], ['signature_method', 'version', 'consumer_key', 'consumer_secret']);
    }

    /**
     * @return array
     */
    public function getEvents(){
        return ['before' => ['onBefore', RequestEvents::SIGN_REQUEST]];
    }

    /**
     * @param BeforeEvent $event
     */
    public function onBefore(BeforeEvent $event){
        $request = $event->getRequest();

        // Only sign requests using "auth"="oauth"
        if ($request->getConfig()['auth'] != 'oauth') {
            return;
        }

        $params = $this->getOauthParams(
            $this->generateNonce($request),
            $this->config
        );

        $params['oauth_signature'] = $this->getSignature($request, $params);
        uksort($params, 'strcmp');

        switch ($this->config['request_method']) {
            case self::REQUEST_METHOD_HEADER:
                list($header, $value) = $this->buildAuthorizationHeader($params);
                $request->setHeader($header, $value);
                break;
            case self::REQUEST_METHOD_QUERY:
                $request->getQuery()->overwriteWith($params);
                break;
            default:
                throw new \InvalidArgumentException(sprintf(
                    'Invalid consumer method "%s"',
                    $this->config['request_method']
                ));
        }
    }

    /**
     * Calculate signature for request
     *
     * @param RequestInterface $request Request to generate a signature for
     * @param array            $params  Oauth parameters.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getSignature(RequestInterface $request, array $params){
        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        unset($params['oauth_signature']);

        // Add POST fields if the request uses POST fields and no files
        $body = $request->getBody();
        if ($body instanceof PostBodyInterface && !$body->getFiles()) {
            $params += Query::fromString($body->getFields(true))->toArray();
        }

        // Parse & add query string parameters as base string parameters
        $params += Query::fromString((string) $request->getQuery())
            ->setEncodingType(Query::RFC1738)
            ->toArray();

        $baseString = $this->createBaseString(
            $request,
            $this->prepareParameters($params)
        );

        // Implements double-dispatch to sign requests
        $meth = [$this, 'sign' . str_replace(
                '-', '', $this->config['signature_method']
            )];

        if (!is_callable($meth)) {
            throw new \RuntimeException('Unknown signature method: '
                . $this->config['signature_method']);
        }

        return base64_encode(call_user_func($meth, $baseString, $this->config));
    }

    /**
     * Returns a Nonce Based on the unique id and URL.
     *
     * This will allow for multiple requests in parallel with the same exact
     * timestamp to use separate nonce's.
     *
     * @param RequestInterface $request Request to generate a nonce for
     *
     * @return string
     */
    public function generateNonce(RequestInterface $request){
        return sha1(uniqid('', true) . $request->getUrl());
    }

    /**
     * Creates the Signature Base String.
     *
     * The Signature Base String is a consistent reproducible concatenation of
     * the request elements into a single string. The string is used as an
     * input in hashing or signing algorithms.
     *
     * @param RequestInterface $request Request being signed
     * @param array            $params  Associative array of OAuth parameters
     *
     * @return string Returns the base string
     * @link http://oauth.net/core/1.0/#sig_base_example
     */
    protected function createBaseString(RequestInterface $request, array $params){
        // Remove query params from URL. Ref: Spec: 9.1.2.
        $url = Url::fromString($request->getUrl())->setQuery('');
        $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        return strtoupper($request->getMethod())
        . '&' . rawurlencode($url)
        . '&' . rawurlencode($query);
    }

    /**
     * Convert booleans to strings, removed unset parameters, and sorts the array
     *
     * @param array $data Data array
     *
     * @return array
     */
    private function prepareParameters($data){
        // Parameters are sorted by name, using lexicographical byte value
        // ordering. Ref: Spec: 9.1.1 (1).
        uksort($data, 'strcmp');

        $data = array_filter($data, function ($value) {
            return $value !== null;
        });

        return $data;
    }

    /**
     * @param $baseString
     * @return string
     */
    private function signHmacSha1($baseString){
        $key = rawurlencode($this->config['consumer_secret'])
            . '&' . rawurlencode($this->config['token_secret']);

        return hash_hmac('sha1', $baseString, $key, true);
    }

    /**
     * @param $baseString
     * @return bool
     */
    private function signRsaSha1($baseString){
        if (!function_exists('openssl_pkey_get_private')) {
            throw new \RuntimeException('RSA-SHA1 signature method '
                . 'requires the OpenSSL extension.');
        }

        $privateKey = openssl_pkey_get_private(
            file_get_contents($this->config['consumer_secret']),
            $this->config['consumer_secret']
        );

        $signature = false;
        openssl_sign($baseString, $signature, $privateKey);
        openssl_free_key($privateKey);

        return $signature;
    }

    /**
     * @param $baseString
     * @return mixed
     */
    private function signPlainText($baseString){
        return $baseString;
    }

    /**
     * Builds the Authorization header for a request
     *
     * @param array $params Associative array of authorization parameters.
     *
     * @return array
     */
    private function buildAuthorizationHeader(array $params){
        foreach ($params as $key => $value) {
            $params[$key] = $key . '="' . rawurlencode($value) . '"';
        }

        if ($this->config['realm']) {
            array_unshift(
                $params,
                'realm="' . rawurlencode($this->config['realm']) . '"'
            );
        }

        return ['Authorization', 'OAuth ' . implode(', ', $params)];
    }

    /**
     * Get the oauth parameters as named by the oauth spec
     *
     * @param string     $nonce  Unique nonce
     * @param Collection $config Configuration options of the plugin.
     *
     * @return array
     */
    private function getOauthParams($nonce, Collection $config){
        $params = [
            'oauth_consumer_key'     => $config['consumer_key'],
            'oauth_nonce'            => $nonce,
            'oauth_signature_method' => $config['signature_method'],
            'oauth_timestamp'        => time(),
        ];

        // Optional parameters should not be set if they have not been set in
        // the config as the parameter may be considered invalid by the Oauth
        // service.
        $optionalParams = [
            'callback'  => 'oauth_callback',
            'token'     => 'oauth_token',
            'verifier'  => 'oauth_verifier',
            'version'   => 'oauth_version'
        ];

        foreach ($optionalParams as $optionName => $oauthName) {
            if (isset($config[$optionName])) {
                $params[$oauthName] = $config[$optionName];
            }
        }

        return $params;
    }
}